<?php
namespace SpaceXStats\Services;

use SpaceXStats\Enums\MissionControlType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Object;
use \Mission;

class ObjectActionService implements ActionServiceInterface {
    protected $object, $tagActioner, $errors;

    public function __construct(\Object $object, TagActionService $tagActioner) {
        $this->object = $object;
        $this->tagActioner = $tagActioner;
    }

    public function isValid($input) {
        $objectValidation = $this->object->isValidForSubmission($input);

        if ($objectValidation === true) {
            return true;
        } else {
            $this->errors = $objectValidation;
            return false;
        }
    }

    public function create($input) {
        $object = Object::find($input['object_id']);

        // Global object properties
        $object->title = $input['title'];
        $object->summary = $input['summary'];

        // Set the mission relation if it exists
        try {
            $mission = Mission::findOrFail($input['mission_id']);
            $object->mission()->associate($mission);

        } catch (ModelNotFoundException $e) {
            // Model not found, do not set
        }

        // Set the tag relations
        $tags = [];
        foreach ($input['tags'] as $tag) {
            try {
                $tag = Tag::where('name', $tag['name'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
            }
            array_push($tags, $tag);
        }

        if ($input['type'] == MissionControlType::Image) {
            $object->attribution = $input['attribution'];
            $object->author = $input['author'];
        }

        $object->save();
    }

    public function getErrors() {
        return $this->errors;
    }
}