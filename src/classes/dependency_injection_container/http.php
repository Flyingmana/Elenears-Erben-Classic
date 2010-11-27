<?php
/**
 * EEC dependency injection container
 *
 * This file is part of arbit.
 *
 * arbit is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 of the License.
 *
 * arbit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with arbit; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package Core
 */

/**
 * EEC dependency injection container
 *
 * Loosely based on the example DIC by Fabien Potencier, as described here:
 * http://fabien.potencier.org/article/17/on-php-5-3-lambda-functions-and-closures
 *
 * @package Core
 */
class EECHttpEnvironmentDIC extends EECEnvironmentDIC
{
    /**
     * Initialize DIC values
     * 
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->requestParser = function( $dic )
        {
            return new arbitHttpRequestParser( $dic->request, $dic->configuration->main );
        };

        $this->request = function( $dic )
        {
            $request = new arbitHttpRequest();
            $request->setSession( $dic->session );

            $request->server = new arbitHttpServerEnvironment( $request );
            $request->body   = new arbitHttpBody( $dic->session, $dic->log );

            $request->body->setConverter( arbitHttpBody::RAW, new arbitHttpNoConverter( $request->encoding ) );
            $request->body->setConverter( arbitHttpBody::TYPE_ARRAY, new arbitHttpArrayConverter( $request->encoding ) );
            $request->body->setConverter( arbitHttpBody::TYPE_STRING, new arbitHttpStringConverter( $request->encoding ) );
            $request->body->setConverter( arbitHttpBody::TYPE_NUMERIC, new arbitHttpNumberConverter( $request->encoding ) );

            return $request;
        };

        $this->session = function( $dic )
        {
            return new arbitHttpSession();
        };

        $this->router = function( $dic, $arguments )
        {
            return new arbitHttpRouter(
                $arguments[0],
                $dic->configuration->main->projects
            );
        };

        $this->responseWriter = function( $dic, $arguments )
        {
            return new ezcMvcHttpResponseWriter( $arguments[0] );
        };
    }
}

