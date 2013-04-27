<?php namespace Orchestra\Foundation;

use Illuminate\Support\Facades\Mail as M;
use Illuminate\Foundation\Application;

class Mail {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Construct a new Mail instance.
	 *
	 * @access public
	 * @param  Illuminate\Foundation\Application    $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}
	
	/**
	 * Allow Orchestra Platform to either use send or queue based on 
	 * settings.
	 *
	 * @access public			
	 * @param  string           $view
	 * @param  array            $data
	 * @param  Closure|string   $callback
	 * @return Illuminate\Mail\Mailer
	 */
	public function send($view, array $data, $callback)
	{
		$method = 'queue';
		$memory = $this->app['orchestra.memory']->make();

		if ('no' === $memory->get('email.queue', 'no')) $method = 'send';

		$forwardTo = array("\Illuminate\Support\Facades\Mail", $method);

		return forward_static_call_array($forwardTo, array($view, $data, $callback));
	}
}