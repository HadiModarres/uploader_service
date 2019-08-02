<?php

namespace UploaderService\Config;

use Symfony\Component\Console\Input\InputInterface;
use UploaderService\Config\Exceptions\InvalidConfigValueException;
use UploaderService\Config\Exceptions\MissingConfigValueException;

/**
 * Class Config
 *
 * @package UploaderService\Config
 */
class Config {

    /**
     * @var string
     */
    protected $path;

    /**
     * @var int
     */
    protected $sizeThreshold;

    /**
     * @var string
     */
    protected $s3Region;

    /**
     * @var string
     */
    protected $s3Bucket;

    /**
     * @var string
     */
    protected $s3Key;

    /**
     * @var string
     */
    protected $s3Secret;

    /**
     * @param string $key
     *
     * @return Config
     * @throws MissingConfigValueException
     */
    protected function ensureSet( string $key ): Config {

        $property = lcfirst( str_replace( ' ', '', ucwords( str_replace( '-', ' ', $key ) ) ) );

        if ( ! isset( $this->$property ) ) {

            throw new MissingConfigValueException( $key );

        }

        return $this;

    }

    /**
     * @param InputInterface $input
     *
     * @return Config
     * @throws InvalidConfigValueException
     */
    public function mergeFromInput( InputInterface $input ): Config {

        if ( '' !== ( $value = $input->getOption( 'path' ) ) ) {

            $this->setPath( $value );

        }

        if ( '' !== ( $value = $input->getOption( 'size-threshold' ) ) ) {

            $this->setSizeThreshold( $value );

        }

        if ( '' !== ( $value = $input->getOption( 's3-region' ) ) ) {

            $this->setS3Region( $value );

        }

        if ( '' !== ( $value = $input->getOption( 's3-bucket' ) ) ) {

            $this->setS3Region( $value );

        }

        if ( '' !== ( $value = $input->getOption( 's3-key' ) ) ) {

            $this->setS3Key( $value );

        }

        if ( '' !== ( $value = $input->getOption( 's3-secret' ) ) ) {

            $this->setS3Secret( $value );

        }

        return $this;

    }

    /**
     * @param string $path
     *
     * @return Config
     * @throws InvalidConfigValueException
     */
    public function setPath( string $path ): Config {

        if ( ! is_dir( $path ) ) {

            throw new InvalidConfigValueException( 'path', $path );

        }

        $this->path = realpath( $path );
        return $this;

    }

    /**
     * @return string
     * @throws MissingConfigValueException
     */
    public function getPath(): string {

        return $this->ensureSet( 'path' )->path;

    }

    /**
     * @param string $sizeThreshold
     *
     * @return Config
     * @throws InvalidConfigValueException
     */
    public function setSizeThreshold( string $sizeThreshold ): Config {

        if ( ! preg_match( '/^(?P<value>(?:\d*\.)?\d+)(?P<unit>k|m|g|)b?$/i', $sizeThreshold, $matches ) ) {

            throw new InvalidConfigValueException( 'sizeThreshold', $sizeThreshold );

        }

        $multipliers = [

            '' => 1,
            'k' => 1024,
            'm' => 1048576,
            'g' => 1073741824,

        ];

        $this->sizeThreshold = (int) round( (float) $matches['value'] * $multipliers[ $matches['unit'] ] );
        return $this;

    }

    /**
     * @return int
     * @throws MissingConfigValueException
     */
    public function getSizeThreshold(): int {

        return $this->ensureSet( 'size-threshold' )->sizeThreshold;

    }

    /**
     * @param string $s3Region
     *
     * @return Config
     * @throws InvalidConfigValueException
     */
    public function setS3Region( string $s3Region ): Config {

        if ( empty( $s3Region ) ) {

            throw new InvalidConfigValueException( 's3-region', $s3Region );

        }

        $this->s3Region = $s3Region;
        return $this;

    }

    /**
     * @return string
     * @throws MissingConfigValueException
     */
    public function getS3Region(): string {

        return $this->ensureSet( 's3-region' )->s3Region;

    }

    /**
     * @param string $s3Bucket
     *
     * @return Config
     * @throws InvalidConfigValueException
     */
    public function setS3Bucket( string $s3Bucket ): Config {

        if ( empty( $s3Bucket ) ) {

            throw new InvalidConfigValueException( 's3-bucket', $s3Bucket );

        }

        $this->s3Bucket = $s3Bucket;
        return $this;

    }

    /**
     * @return string
     * @throws MissingConfigValueException
     */
    public function getS3Bucket(): string {

        return $this->ensureSet( 's3-bucket' )->s3Bucket;

    }

    /**
     * @param string $s3Key
     *
     * @return Config
     * @throws InvalidConfigValueException
     */
    public function setS3Key( string $s3Key ): Config {

        if ( empty( $s3Key ) ) {

            throw new InvalidConfigValueException( 's3-key', $s3Key );

        }

        $this->s3Key = $s3Key;
        return $this;

    }

    /**
     * @return string
     * @throws MissingConfigValueException
     */
    public function getS3Key(): string {

        return $this->ensureSet( 's3-key' )->s3Key;

    }

    /**
     * @param string $s3Secret
     *
     * @return Config
     * @throws InvalidConfigValueException
     */
    public function setS3Secret( string $s3Secret ): Config {

        if ( empty( $s3Secret ) ) {

            throw new InvalidConfigValueException( 's3-secret', $s3Secret );

        }

        $this->s3Secret = $s3Secret;
        return $this;

    }

    /**
     * @return string
     * @throws MissingConfigValueException
     */
    public function getS3Secret(): string {

        return $this->ensureSet( 's3-secret' )->s3Secret;

    }

}