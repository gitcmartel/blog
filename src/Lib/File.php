<?php

namespace Application\Lib;

class File
{
    #region Properties
    private ?array $filePath;
    private ?string $filePathError;
    private ?string $filePathSize;
    private ?string $filePathName;
    private ?string $filePathTmpName;
    #endregion

    #Region Constructor
    public function __construct(array $files)
    {
        $this->filePath = $files['imagePath'];
        $this->filePathError = $files['imagePath']['error'];
        $this->filePathSize = $files['imagePath']['size'];
        $this->filePathName = $files['imagePath']['name'];
        $this->filePathTmpName = $files['imagePath']['tmp_name'];
    }

    #region Getters and setters
    /**
     * Get file Path
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * Set file Path
     * @param string $filePath
     */
    public function setFilePath(string $filePath)
    {
        $this->imagePath = $filePath;
    }

    /**
     * Get file Path Error
     * @return string
     */
    public function getfilePathError(): string
    {
        return $this->filePathError;
    }

    /**
     * Set file Path error
     * @param string $filePathError
     */
    public function setfilePathError(string $filePathError)
    {
        $this->filePathError = $filePathError;
    }

    /**
     * Get file Path size
     * @return string
     */
    public function getfilePathSize(): string
    {
        return $this->filePathSize;
    }

    /**
     * Set file Path size
     * @param string $filePathSize
     */
    public function setfilePathSize(string $filePathSize)
    {
        $this->filePathSize = $filePathSize;
    }

    /**
     * Get file Path name
     * @return string
     */
    public function getfilePathName(): string
    {
        return $this->filePathName;
    }

    /**
     * Set file Path Name
     * @param string $filePathName
     */
    public function setfilePathName(string $filePathName)
    {
        $this->filePathName = $filePathName;
    }

    /**
     * Get file Path tmp name
     * @return string
     */
    public function getfilePathTmpName(): string
    {
        return $this->filePathTmpName;
    }

    /**
     * Set file Path tmp name
     * @param string $filePathTmpName
     */
    public function setfilePathTmpName(string $filePathTmpName)
    {
        $this->filePathTmpName = $filePathTmpName;
    }
    #endregion

    #region Functions
    /**
     * Check the size and type of a file
     * @return string
     */
    public function ckeckFile(): string
    {
        if ($this->checkFileType() !== true) {
            return 'Le type de fichier doit être une image (jpeg, jpg, png, svg)';
        }

        if ($this->checkSize() !== true) {
            return 'La taille de l\'image ne doit pas excéder 2 Mo';
        }

        return '';
    }

    /**
     * Checks if the file extension is one of thoses who are allowed
     * @return bool
     */
    private function checkFileType(): bool
    {
        $fileExtension = self::getExtension($this->filePathName);

        if (in_array($fileExtension, Constants::IMAGE_EXTENSIONS) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks if the file size does not exceeds the limit allowed
     * @return bool
     */
    private function checkSize(): bool
    {
        if ($this->filePathSize > Constants::IMAGE_MAX_SIZE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the extension of a file
     * @param string $fileName
     * @return string
     */
    public static function getExtension(string $fileName): string
    {
        $explode = explode(".", $fileName);
        return strtolower(end($explode));
    }

    /**
     * Checks a file and validates it or not
     * @return bool
     */
    public function validateFile(): bool
    {
        return (
            $this->filePath !== null &&
            $this->filePathError !== UPLOAD_ERR_OK &&
            $this->filePathSize > 0
        );
    }
    #endregion
}
