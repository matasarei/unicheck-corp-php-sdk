<?php namespace Unicheck\Corporate\Check;

use Unicheck\Corporate\Exception\CheckException;

/**
 * Class CheckParam
 */
class CheckParam
{
    const TYPE_MY_LIBRARY = "my_library";
    const TYPE_WEB = "web";
    const TYPE_EXTERNAL_DB = "external_database";
    const TYPE_DOC_VS_DOC = "doc_vs_docs";
    const TYPE_WEB_AND_MY_LIBRARY = "web_and_my_library";

    /**
     * @var array $typeMap
     */
    protected static $typeMap =
        [
            self::TYPE_MY_LIBRARY,
            self::TYPE_WEB,
            self::TYPE_EXTERNAL_DB,
            self::TYPE_DOC_VS_DOC,
            self::TYPE_WEB_AND_MY_LIBRARY
        ];

    /**
     * @var int
     */
    protected $file_id;

    /**
     * @var int[]
     */
    protected $versus_files = [];

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var string $callback_url
     */
    protected $callback_url;

    /**
     * @var bool $exclude_citations
     * @default false
     */
    protected $exclude_citations = false;

    /**
     * @var bool $exclude_references (default: false)
     * @default false
     */
    protected $exclude_references = false;

    /**
     * @var bool
     */
    protected $exclude_self_plagiarism = false;

    /**
     * @var int
     */
    protected $words_sensitivity = 8;

    /**
     * @var float
     */
    protected $sensitivity = 0;

    /**
     * CheckParam constructor.
     * @param $file_id
     * @throws CheckException
     */
    public function __construct($file_id)
    {
        if( is_numeric($file_id) === false )
        {
            throw new CheckException("File ID must be Integer.");
        }

        $this->file_id = $file_id;
        $this->type = self::TYPE_WEB; // set default type - WEB

    }

    /**
     * @param bool $exclude_self_plagiarism
     */
    public function setExcludeSelfPlagiarism($exclude_self_plagiarism)
    {
        $this->exclude_self_plagiarism = (bool) $exclude_self_plagiarism;
    }

    /**
     * @param float $sensitivity
     */
    public function setSensitivity($sensitivity)
    {
        if ($sensitivity < 0 || $sensitivity > 1) {
            throw new \InvalidArgumentException('Unexpected value, sensitivity must be a float from 0 to 1.0');
        }

        $this->sensitivity = (float)$sensitivity;
    }

    /**
     * @param int $words_sensitivity
     */
    public function setWordsSensitivity($words_sensitivity)
    {
        if ($words_sensitivity < 8 || $words_sensitivity > 999) {
            throw new \InvalidArgumentException('Unexpected value, words sensitivity must be an integer from 8 to 9999');
        }

        $this->words_sensitivity = $words_sensitivity;
    }

    /**
     * Method setType description.
     * @param $type
     * @param null $versusFiles
     *
     * @return $this
     * @throws CheckException
     */
    public function setType($type, $versusFiles = null)
    {
        if( array_search($type, self::$typeMap) === false )
        {
            throw new CheckException(
                sprintf(
                    "<b>Set invalid type: '{$type}'</b>. Allowed check type is '%s'",
                    implode("', '", self::$typeMap)
                )
            );
        }

        if( $type === self::TYPE_DOC_VS_DOC )
        {

            if( empty($versusFiles) )
            {
                throw new CheckException("Versus Files can not be empty for check type '{$type}'");
            }
            else
            {
                $this->versus_files = $versusFiles;
            }
        }

        $this->type = $type;
        return $this;
    }

    /**
     * Method setCallbackUrl description.
     * @param $url
     *
     * @return $this
     */
    public function setCallbackUrl($url)
    {
        $this->callback_url = $url;
        return $this;
    }

    /**
     * Method setExcludeCitations description.
     * @param $exclude_citations
     *
     * @return $this
     */
    public function setExcludeCitations($exclude_citations)
    {
        $this->exclude_citations = (bool) $exclude_citations;
        return $this;
    }

    /**
     * Method setExcludeReferences description.
     * @param $exclude_references
     *
     * @return $this
     */
    public function setExcludeReferences($exclude_references)
    {
        $this->exclude_references = (bool) $exclude_references;
        return $this;
    }

    /**
     * Method mergeParams description.
     *
     * @return array
     */
    public function mergeParams()
    {
        $options = [
            'words_sensitivity' => $this->words_sensitivity,
            'exclude_citations' => $this->exclude_citations ? 1 : 0,
            'exclude_references' => $this->exclude_references ? 1 : 0,
            'exclude_self_plagiarism' => $this->exclude_self_plagiarism ? 1 : 0
        ];

        if ($this->sensitivity > 0) {
            $options['sensitivity'] = $this->sensitivity;
        }

        $params = [
            'file_id' => $this->file_id,
            'type' => $this->type,
            'options' => $options
        ];

        if (!empty($this->versus_files)) {
            $params['versus_files'] = $this->versus_files;
        }

        if (!empty($this->callback_url)) {
            $params['callback_url'] = $this->callback_url;
        }

        return $params;
    }
}
