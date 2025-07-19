<?php

declare(strict_types=1);

namespace App\Support\Filepond;

use ErrorException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use LogicException;

class Image
{
    public const DISC = 'public';
    public const TEMPORARY_CATALOG = 'tmp';
    public const PERMANENT_CATALOG = 'images';

    public string $path;

    /**
     * @throws ErrorException
     */
    public function __construct(string $path)
    {
        $this->path = $path;

        if (!$this->isExists()) {
            throw new ErrorException('Image is not exists');
        }
    }

    public static function from(string $path): static
    {
        return new self($path);
    }

    public static function createIfExists(string $path): static|false
    {
        try {
            return new self($path);
        } catch (ErrorException $e) {
            return false;
        }
    }

    public static function fromUploadedFile(UploadedFile $file): static
    {
        return new self($file->store(self::TEMPORARY_CATALOG, self::DISC));
    }

    public function isTemporary(): bool
    {
        return str_contains($this->path, self::TEMPORARY_CATALOG.DIRECTORY_SEPARATOR);
    }

    public function isPermanent(): bool
    {
        return !$this->isTemporary();
    }

    public function isExists(): bool
    {
        return static::exists($this->path);
    }

    /**
     * @throws ErrorException
     * @throws LogicException
     */
    public function publish(): static
    {
        if ($this->isPermanent()) {
            throw new LogicException("Image $this->path is not temporary");
        }

        $newPath = str_replace(self::TEMPORARY_CATALOG, self::PERMANENT_CATALOG.DIRECTORY_SEPARATOR.date('Y-m'), $this->path);

        if (Storage::disk(self::DISC)->move($this->path, $newPath)) {
            $image = clone $this;
            $image->path = $newPath;

            return $image;
        }

        throw new ErrorException("Image $this->path could not be published");
    }

    public function delete(): void
    {
        static::remove($this->path);
    }

    /**
     * @throws ErrorException
     */
    public function publishIfTemporary(): static
    {
        try {
            return $this->publish();
        } catch (LogicException $e) {
            return clone $this;
        }
    }

    public function get(): string
    {
        return Storage::disk(self::DISC)->get($this->path);
    }

    public function getUrl(): string
    {
        return Storage::disk(self::DISC)->url($this->path);
    }

    public function getGlobalPath(): string
    {
        return 'storage'.DIRECTORY_SEPARATOR.$this->path;
    }

    public function getName(): string
    {
        return basename($this->path);
    }

    public function getSize(): int
    {
        return Storage::disk(self::DISC)->size($this->path);
    }

    public function getType(): string
    {
        return Storage::disk(self::DISC)->mimeType($this->path);
    }

    public function __toString(): string
    {
        return $this->path;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'globalPath' => $this->getGlobalPath(),
            'url' => $this->getUrl(),
            'name' => $this->getName(),
            'size' => $this->getSize(),
            'type' => $this->getType(),
        ];
    }

    public static function exists(string $path): bool
    {
        return Storage::disk(self::DISC)->exists($path);
    }

    public static function remove(string $path): bool
    {
        return Storage::disk(self::DISC)->delete($path);
    }
}
