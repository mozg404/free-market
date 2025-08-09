<?php

declare(strict_types=1);

namespace App\Support;

use ErrorException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use LogicException;

final class Image implements Arrayable
{
    public const DISC = 'public';
    public const TEMPORARY_CATALOG = 'tmp';
    public const PERMANENT_CATALOG = 'images';

    protected function __construct(
        private string $relativePath
    ) {
    }

    public static function from(string|self|UploadedFile|null $searchable): ?self
    {
        if (is_null($searchable) || $searchable instanceof self) {
            return $searchable;
        }

        if ($searchable instanceof UploadedFile) {
            return self::createFromUploadedFile($searchable);
        }

        if (self::existsFromRelativePath($searchable)) {
            return new self($searchable);
        }

        if (self::existsFromUrl($searchable)) {
            return self::createFromUrl($searchable);
        }

        return null;
    }

    /**
     * Создает объект из URL
     * @param string $url
     * @return self
     */
    public static function createFromUrl(string $url): self
    {
        return new self(self::getRelativePathFromUrl($url));
    }

    /**
     * Создает объект из загруженного файла UploadedFile
     * @param UploadedFile $file
     * @return static
     */
    public static function createFromUploadedFile(UploadedFile $file): static
    {
        return new self($file->store(self::TEMPORARY_CATALOG, self::DISC));
    }

    /**
     * Создает объект, используя полный путь до файла
     *
     * @param string $fullPath
     * @return $this
     */
    public static function createFromAbsolutePath(string $fullPath): static
    {
        // Проверка на существование
        if (!file_exists($fullPath)) {
            throw new InvalidArgumentException('File not found: ' . $fullPath);
        }

        // Вычисление имени файла
        $hash = sha1_file($fullPath);
        $randomName = substr($hash, 0, 40) . Str::random(10);
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $id = self::TEMPORARY_CATALOG . DIRECTORY_SEPARATOR . $randomName . '.' . $extension;

        // Копирование файла в директорию TMP
        self::disk()->put($id, file_get_contents($fullPath));

        // Отдача объекта этого изображения
        return new self($id);
    }

    public function isTemporary(): bool
    {
        return str_contains($this->relativePath, self::TEMPORARY_CATALOG . DIRECTORY_SEPARATOR);
    }

    public function isPublished(): bool
    {
        return !$this->isTemporary();
    }

    public function isExists(): bool
    {
        return self::exists($this->relativePath);
    }

    /**
     * Публикует изображение в постоянное хранилище
     *
     * @throws ErrorException
     * @throws LogicException
     */
    public function publish(): self
    {
        if ($this->isPublished()) {
            throw new LogicException("Image $this->relativePath is not temporary");
        }

        $newPath = str_replace(self::TEMPORARY_CATALOG, self::PERMANENT_CATALOG . DIRECTORY_SEPARATOR . date('Y-m'), $this->relativePath);

        if (self::disk()->move($this->relativePath, $newPath)) {
            $image = clone $this;
            $image->relativePath = $newPath;

            return $image;
        }

        throw new ErrorException("Image $this->relativePath could not be published");
    }

    /**
     * Публикует изображение в постоянное хранилище, если файл находится во временном
     * Иначе просто возвращает объект существующего
     *
     * @throws ErrorException
     */
    public function publishIfTemporary(): self
    {
        try {
            return $this->publish();
        } catch (LogicException $e) {
            return clone $this;
        }
    }

    /**
     * Удаляет изображение
     *
     * @return void
     */
    public function delete(): void
    {
        self::remove($this->relativePath);
    }

    public function getContent(): string
    {
        return self::disk()->get($this->relativePath);
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function getUrl(): string
    {
        return self::disk()->url($this->relativePath);
    }

    public function getUri(): string
    {
        return '/storage' . DIRECTORY_SEPARATOR . $this->relativePath;
    }

    public function getName(): string
    {
        return basename($this->relativePath);
    }

    public function getSize(): int
    {
        return self::disk()->size($this->relativePath);
    }

    public function getType(): string
    {
        return self::disk()->mimeType($this->relativePath);
    }

    public function __toString(): string
    {
        return $this->getUrl();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->relativePath,
            'uri' => $this->getUri(),
            'url' => $this->getUrl(),
            'name' => $this->getName(),
            'size' => $this->getSize(),
            'type' => $this->getType(),
        ];
    }

    public static function exists(string $searchable): bool
    {
        return match (true) {
            self::existsFromRelativePath($searchable) => true,
            self::existsFromUrl($searchable) => true,
            default => false,
        };
    }

    /**
     * Проверка на существование по внутреннему ID
     * @param string $relativePath
     * @return bool
     */
    public static function existsFromRelativePath(string $relativePath): bool
    {
        return self::disk()->exists($relativePath);
    }

    /**
     * Проверка на существование по URL
     * @param string $url
     * @return bool
     */
    public static function existsFromUrl(string $url): bool
    {
        return self::disk()->exists(self::getRelativePathFromUrl($url));
    }

    public static function remove(string $id): bool
    {
        return self::disk()->delete($id);
    }

    public static function disk(): Filesystem
    {
        return Storage::disk(self::DISC);
    }

    private static function getRelativePathFromUrl(string $url): string
    {
        return str_replace(self::disk()->url(''), '', $url);
    }
}
