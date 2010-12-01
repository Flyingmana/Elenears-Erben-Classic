<?php
/**
 * EEC overview base controller
 *
 * @package Overview
 */

/**
 * Base overview controller.
 *
 * @package Overview
 */
class EECModuleOverviewController extends arbitModuleController
{
    /**
     * Index action
     *
     * The index action just dispatches to the default view of the overview
     * module
     *
     * @param arbitRequest $request
     * @return arbitViewModuleModel
     */
    public function index( arbitRequest $request )
    {
        return new EECOverviewViewModel();
    }

    /**
     * collect action
     *
     * collect data from other modules
     *
     * @param arbitRequest $request
     * @return arbitViewModuleModel
     */
    public function collect( arbitRequest $request)
    {

    }


    /**
     * Overview action
     *
     * Show user an overview of all PhpDoc questions
     *
     * @param arbitRequest $request
     * @return arbitViewModuleModel
     */
    public function view( arbitRequest $request )
    {
        if ( !$request->session->may( 'php_doc_view' ) )
        {
            return new arbitViewModuleModel( $request->action, $this->getMenu(), new arbitViewUserMessageModel( 'No permission.' ) );
        }

        try
        {
            // Check if the requested file exists
            if ( !is_file( $file = $this->getDocPath( $request ) . $request->path ) )
            {
                throw new arbitModulePhpDocNotFoundException( $request->path );
            }

            return new arbitViewModuleModel(
                $request->action,
                $this->getMenu(),
                new arbitPhpDocHtmlViewModel( file_get_contents( $file ) )
            );
        }
        catch ( arbitModulePhpDocNotFoundException $e )
        {
            return new arbitViewModuleModel(
                $request->action,
                $this->getMenu(),
                new arbitViewUserMessageModel(
                    $e->getText(),
                    $e->getTextValues()
                )
            );
        }
    }

    /**
     * Log action
     *
     * Display the documentation rendering log
     *
     * @param arbitRequest $request
     * @return arbitViewModuleModel
     */
    public function log( arbitRequest $request )
    {
        if ( !$request->session->may( 'php_doc_view_log' ) )
        {
            return new arbitViewModuleModel( $request->action, $this->getMenu(), new arbitViewUserMessageModel( 'No permission.' ) );
        }

        if ( ( $errors = $this->env->cache->get( 'phpdoc_path', 'errors' ) ) === false )
        {
            return new arbitViewModuleModel( $request->action, $this->getMenu(), new arbitViewUserMessageModel( 'No error log available.' ) );
        }

        return new arbitViewModuleModel(
            $request->action,
            $this->getMenu(),
            new arbitPhpDocLogViewModel( $errors )
        );
    }

    /**
     * Handle source update signal
     *
     * @param arbitEnvironmentDIC $env 
     * @param string $signal 
     * @param arbitSignalSlotStruct $struct 
     * @return void
     */
    public static function sourceUpdated( arbitEnvironmentDIC $env, $signal, arbitSignalSlotStruct $struct )
    {
        switch ( $signal )
        {
            case 'sourceUpdated':
                $checkout = $struct->checkout->getViewModel( '/' );
                $env->cache->cache( 'phpdoc_path', 'path', $checkout );
                break;
        }
    }

    /**
     * Parse log from PHP documentor
     *
     * Parse the log and return an array of errors and warnings associated with
     * the respective file and line where the problem occured.
     *
     * @param string $log
     * @return array
     */
    protected function parsePhpDocLog( $log, $basepath )
    {
        $lines  = preg_split( '(\r\n|\r|\n)', $log );
        $file   = null;
        $errors = array();
        foreach ( $lines as $line )
        {
            switch ( true )
            {
                case preg_match( '(^(?:Reading\s+file|Processing\s+.+\s+in\s+file)\s+(?P<file>.+?)(?:\s+--|$))', $line, $match ):
                    $file = $match['file'];
                    break;

                case $file && preg_match( '(^\s*(?P<type>WARNING|ERROR)\s+in\s+(?P<basename>.*)\s+on\s+line\s+(?P<line>\d+):\s+(?P<message>.*)$)', $line, $match ):
                    $errors[] = array(
                        'type'    => $match['type'] === 'ERROR' ? E_ERROR : E_WARNING,
                        'file'    => str_replace( $basepath, '', $file ),
                        'line'    => (int) $match['line'],
                        'message' => $match['message'],
                    );
                    break;
            }
        }
        return $errors;
    }

    /**
     * Report errors back to source module
     *
     * @param arbitRequest $request
     * @param array $errors
     * @return void
     */
    protected function reportErrors( arbitRequest $request, array $errors )
    {
        foreach ( $errors as $nr => $error )
        {
            $this->env->signalSlotHandler->emit(
                'sourceAnnotate',
                new arbitSourceAnnotateStruct(
                    'phpdoc-' . ( $error['type'] === E_ERROR ? 'error' : 'warning' ),
                    $error['message'],
                    "{$request->controller}/{$request->action}/log#error-$nr",
                    $error['file'],
                    $error['line']
                )
            );
        }
    }

    /**
     * Get path to generated documentation
     *
     * @param arbitRequest $request
     * @return string
     */
    protected function getDocPath( arbitRequest $request )
    {
        return ARBIT_CACHE_PATH . $request->controller . '/phpdoc/doc/';
    }

    /**
     * Emit continious integration update
     *
     * @param arbitSourceCheckoutViewModel $model
     * @param arbitRequest $request
     * @return void
     */
    protected function emitCiSignals( arbitSourceCheckoutViewModel $model, arbitRequest $request )
    {
        // Only emit signals, if the CI module is installed for this project
        if ( !array_search( 'ci', $request->variables['arbit_modules'] ) )
        {
            return;
        }

        return;
        // @TODO: We need to create some kind of XML log first, here.
        $this->env->signalSlotHandler->emit(
            'ciModuleResult',
            new arbitCiModuleResultStruct(
                'pdepend', 'summary',
                $this->getLogPath( $request ) . 'summary.xml',
                $model->version,
                new DateTime()
            )
        );
    }

    /**
     * Generate action
     *
     * (Re-)Generate the API documentation
     *
     * @param arbitRequest $request
     * @return arbitViewModuleModel
     */
    public function generate( arbitRequest $request )
    {
        if ( !$request->session->may( 'php_doc_generate' ) )
        {
            return new arbitViewModuleModel( $request->action, $this->getMenu(), new arbitViewUserMessageModel( 'No permission.' ) );
        }

        if ( ( $struct = $this->env->cache->get( 'phpdoc_path', 'path' ) ) === false )
        {
            return new arbitViewModuleModel( $request->action, $this->getMenu(), new arbitViewUserMessageModel( 'No source check out reported.' ) );
        }

        // Generate API documentation
        $process = new arbitSystemProcess( 'phpdoc' );

        $process->argument( '--directory' )
            ->argument( $base = $struct->base );
        $process->argument( '--templatebase' )
            ->argument( __DIR__ . '/../data/phpdoc/' );
        $process->argument( '--target' )
            ->argument( $this->getDocPath( $request ) );
        $process->argument( '--output' )
            ->argument( 'HTML:Arbit:arbit' );

        // Append additional options from ini file
        $options = parse_ini_file( __DIR__ . '/../config.ini' );
        foreach ( $options as $key => $value )
        {
            switch ( $key )
            {
                case 'hidden':
                    $process->argument( '--' . $key )->argument( $value ? 'true' : 'false' );
                    break;
                case 'parseprivate':
                case 'javadocdesc':
                    $process->argument( '--' . $key )->argument( $value ? 'on' : 'off' );
                    break;
                default:
                    $process->argument( '--' . $key )->argument( $value );
            }
        }

        $process->execute();
        $this->env->cache->cache( 'phpdoc_path', 'log', $process->stdoutOutput );

        // Extract errors from log and report them back to the source module
        ezcLog::getInstance()->log( "Parsing phpdoc output.", ezcLog::INFO );
        $errors = $this->parsePhpDocLog( $process->stdoutOutput, $base );
        $this->env->cache->cache( 'phpdoc_path', 'errors', $errors );
        $this->reportErrors( $request, $errors );
        $this->emitCiSignals( $struct, $request );

        // We regenerated the API documentation for this revision, remove the
        // cache, so it will only be regenrated again after an update
        $this->env->cache->purge( 'phpdoc_path', 'path' );

        return new arbitViewModuleModel( $request->action, $this->getMenu(), new arbitViewUserMessageModel( 'Regenerated API documentation.' ) );
    }
}

