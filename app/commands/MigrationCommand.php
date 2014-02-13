<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MigrationCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'oldsite:convert';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Convert the new.sv.com forums and messages to L4.';

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
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->moveGamesTable();

		$this->info('Finished with conversion');
	}

	protected function addOldIdColumn($tableName)
	{
		if (!Schema::hasColumn($tableName, 'oldId')) {
			Schema::table($tableName, function(Blueprint $table) {
				$table->string('oldId', 10)->index();
			});
		}
	}

	protected function getIdForOldId($class, $oldId)
	{
		$object = $class::where('oldId', $oldId)->first();

		if ($object == null) {
			return 0;
		}

		return $object->id;
	}

	protected function getTypeId($typeKeyName)
	{
		return Type::where('keyName', $typeKeyName)->first()->id;
	}

	protected function moveGamesTable()
	{
		if ($this->confirm('Do you wish to move games? [yes|no]')) {
			// $this->addOldIdColumn('user_favorites');
			// Move the characters
			$objects = DB::table('stygian_ahscore.round_winners')->get();

			foreach ($objects as $object) {
					$newObject           = new Round_Winner;
					$newObject->round_id = $this->getIdForOldId('Round', $object->round_id);
					if ($object->morph_type == 'Member') {
						$newObject->morph_id   = $this->getIdForOldId('Actor', $object->morph_id);
						$newObject->morph_type = 'Actor';
					} else {
						$newObject->morph_id   = $this->getIdForOldId('Team', $object->morph_id);
						$newObject->morph_type = 'Team';
					}
					$newObject->created_at    = $object->created_at;
					$newObject->updated_at    = $object->updated_at;
					// $newObject->deleted_at = $object->deleted_at;
					// $newObject->oldId      = $object->id;

					$newObject->save();
			}
			$this->info('Games moved');
		} else {
			$this->info('Games skipped');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}