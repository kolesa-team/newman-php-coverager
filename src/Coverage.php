<?php
namespace newmanPhpCoverager;

use SebastianBergmann\CodeCoverage\CodeCoverage;

/**
 * Class Coverage
 * @package newmanPhpCoverager
 */
class Coverage
{
    /**
     * Default test name
     * @var string
     */
    protected $defaultTestName = 'DEBUG';

    /**
     * Default output directory
     * @var string
     */
    protected $defaultDir = 'phpnewman';

    /**
     * Default directories assigned to white list
     * @var array
     */
    protected $defaultWhiteList = [
        'applications',
        'console',
        'common',
    ];

    /**
     * Config array
     * @var array
     */
    protected $config;

    /**
     * Test name
     * @var
     */
    protected $testName;

    /**
     * CodeCoverage Object
     * @var \SebastianBergmann\CodeCoverage\CodeCoverage
     */
    protected $phpCC;

    /**
     * Event start switcher
     * @var
     */
    private $_listenStarted;

    /**
     * Coverage constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config = [])
    {
        if ($this->getHeader('HTTP_PHPNEWMAN_ON') == 1) {
            if (empty($config)) {
                $this->config = [
                    'testName' => $this->defaultTestName,
                    'path'     => $_SERVER['DOCUMENT_ROOT'] . '/../../' . $this->defaultDir . "/" . $this->config['testName'],
                ];

                $this->config = array_merge($this->config, $this->loadConfig());
            } else {
                $this->config = $config;
            }

            if ($this->prepareDir()) {
                $this->phpCC = new CodeCoverage();
                $this->addToWhiteList();
                $this->startListen();
            } else {
                throw new \Exception("Can't prepare working directory " . $this->config['path']);
            }
        }
    }

    /**
     * Clear folder data
     */
    public function clear()
    {
        $this->phpCC->clear();
    }

    /**
     * Load config from header
     *
     * @return array|mixed
     */
    public function loadConfig()
    {
        if ($this->getHeader('HTTP_PHPNEWMAN')) {
            return (array)json_decode($this->getHeader('HTTP_PHPNEWMAN'), true);
        } else {
            return [];
        }
    }

    /**
     * Get header variable
     *
     * @param $name
     * @return null
     */
    public function getHeader($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }

        return null;
    }

    /**
     * Let start listening event
     *
     * @return bool
     */
    public function startListen()
    {
        if ($this->_listenStarted) {

            return false;
        } else {
            $this->_listenStarted = true;

            $this->phpCC->start($this->config['testName']);
        }
    }

    /**
     * Prepare dir for writing
     *
     * @return bool
     */
    protected function prepareDir()
    {
        if (!is_dir($this->config['path'])) {
            return mkdir($this->config['path']);
        } else {
            return true;
        }
    }

    /**
     * Add path to whitelist
     *
     * @return bool
     */
    public function addToWhiteList()
    {
        if ($this->_listenStarted) {

            return false;
        } else {
            foreach ($this->defaultWhiteList as $dir) {
                $this->phpCC->filter()->addDirectoryToWhitelist($_SERVER['DOCUMENT_ROOT'] . '/../../' . $dir);
            }
        }
    }

    /**
     * Stop listening event
     *
     * @return bool
     */
    public function stopListen()
    {
        if (!$this->_listenStarted) {

            return false;
        } else {
            $this->phpCC->stop();

            $this->_listenStarted = false;
        }
    }

    /**
     * Publish report to path
     *
     * @param string $type
     */
    public function publishReport($type = 'php')
    {
        switch ($type) {
            case 'php':
                $reporter = new \SebastianBergmann\CodeCoverage\Report\PHP;
                break;

            case 'html':
                $reporter = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
                break;

            case 'xml':
                $reporter = new \SebastianBergmann\CodeCoverage\Report\Xml\Facade('6.5.6');
                break;

            case 'clover':
                $reporter = new \SebastianBergmann\CodeCoverage\Report\Clover;
                break;
            default :
                $reporter = new \SebastianBergmann\CodeCoverage\Report\PHP;
        }

        $reporter->process(
            $this->phpCC, $this->config['path']
            . "/"
            . str_replace(".", "", microtime(true))
            . '.cov'
        );

    }

    /**
     * Destructurisatoin trigger
     */
    public function __destruct()
    {
        $this->stopListen();
        $this->publishReport();
    }
}