<?php

declare(strict_types=1);

namespace App\Support\Filepond;

use ErrorException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use LogicException;

class Image
{
    public const DISC = 'public';
    public const TEMPORARY_CATALOG = 'tmp';
    public const PERMANENT_CATALOG = 'images';

    public string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Создает объект из уже существующего файла по ID
     *
     * @param string $id
     * @return static
     * @throws ErrorException
     */
    public static function from(string $id): static
    {
        if (!static::exists($id)) {
            throw new ErrorException('Image is not exists');
        }

        return new self($id);
    }

    /**
     * Создает изображение из загруженного файла
     *
     * @param UploadedFile $file
     * @return static
     */
    public static function createFromUploadedFile(UploadedFile $file): static
    {
        return new self($file->store(self::TEMPORARY_CATALOG, self::DISC));
    }

    /**
     * Создает изображение, используя полный путь до файла
     *
     * @param string $fullPath
     * @return $this
     * @throws ErrorException
     */
    public static function createFromPath(string $fullPath): static
    {
        // Проверка на существование
        if (!file_exists($fullPath)) {
            throw new InvalidArgumentException('File not found: ' . $fullPath);
        }

        // Вычисление имени файла
        $hash = sha1_file($fullPath);
        $randomName = substr($hash, 0, 40) . Str::random(10);
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $id = static::TEMPORARY_CATALOG.DIRECTORY_SEPARATOR.$randomName.'.'.$extension;

        // Копирование файла в директорию TMP
        Storage::disk(self::DISC)->put($id, file_get_contents($fullPath));

        // Отдача объекта этого изображения
        return new self($id);
    }

    public static function createIfExists(string $path): static|false
    {
        try {
            return new self($path);
        } catch (ErrorException $e) {
            return false;
        }
    }

    public function isTemporary(): bool
    {
        return str_contains($this->id, self::TEMPORARY_CATALOG.DIRECTORY_SEPARATOR);
    }

    public function isPermanent(): bool
    {
        return !$this->isTemporary();
    }

    public function isExists(): bool
    {
        return static::exists($this->id);
    }

    /**
     * Публикует изображение в постоянное хранилище
     *
     * @throws ErrorException
     * @throws LogicException
     */
    public function publish(): static
    {
        if ($this->isPermanent()) {
            throw new LogicException("Image $this->id is not temporary");
        }

        $newPath = str_replace(self::TEMPORARY_CATALOG, self::PERMANENT_CATALOG.DIRECTORY_SEPARATOR.date('Y-m'), $this->id);

        if (Storage::disk(self::DISC)->move($this->id, $newPath)) {
            $image = clone $this;
            $image->id = $newPath;

            return $image;
        }

        throw new ErrorException("Image $this->id could not be published");
    }

    /**
     * Публикует изображение в постоянное хранилище, если файл находится во временном
     * Иначе просто возвращает объект существующего
     *
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

    /**
     * Удаляет изображение
     *
     * @return void
     */
    public function delete(): void
    {
        static::remove($this->id);
    }

    public function get(): string
    {
        return Storage::disk(self::DISC)->get($this->id);
    }

    public function getUrl(): string
    {
        return Storage::disk(self::DISC)->url($this->id);
    }

    public function getGlobalPath(): string
    {
        return 'storage'.DIRECTORY_SEPARATOR.$this->id;
    }

    public function getName(): string
    {
        return basename($this->id);
    }

    public function getSize(): int
    {
        return Storage::disk(self::DISC)->size($this->id);
    }

    public function getType(): string
    {
        return Storage::disk(self::DISC)->mimeType($this->id);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->id,
            'globalPath' => $this->getGlobalPath(),
            'url' => $this->getUrl(),
            'name' => $this->getName(),
            'size' => $this->getSize(),
            'type' => $this->getType(),
        ];
    }

    public static function exists(string $id): bool
    {
        return Storage::disk(self::DISC)->exists($id);
    }

    public static function remove(string $id): bool
    {
        return Storage::disk(self::DISC)->delete($id);
    }
}
