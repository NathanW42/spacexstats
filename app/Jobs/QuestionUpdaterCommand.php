<?php

namespace SpaceXStats\Commands;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class QuestionUpdaterCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'QuestionUpdater';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update the list of FAQs from the /r/SpaceX Wiki daily';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * When a command should run
	 *
	 * @param Scheduler $scheduler
	 * @return \Indatus\Dispatcher\Scheduling\Schedulable
	 */
	public function schedule(Schedulable $scheduler)
	{
        // Figure out when activity is the least and run it then
		return $scheduler->daily()->hours(9);
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('test');
		exec('C:\Python34\python H:\spacexstatsv4\app\python\questionPopulator.py');
	}
}