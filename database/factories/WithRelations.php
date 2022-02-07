<?php

namespace Database\Factories;

trait WithRelations {
    
    /**
     * Expand all attributes to their underlying values.
     * This is Override the parent method
     * @param  array  $definition
     * @return array
     */
    protected function expandAttributes(array $definition)
    {
        return parent::expandAttributes(array_merge($definition, $this->submitRelations()));
    }

    /**
     * Submit the relationships for the model.
     * @return array
     */
    protected function submitRelations()
    {
        $resualt = [];
        foreach ($this->relations() as $foreign_key => $relation_name) {
            $related_model = app($this->model)->{$relation_name}()->getRelated();
            $resualt[$foreign_key] = $related_model->factory()->create()->id;
        }

        return $resualt;
    }

    /**
     * Define the model's foregin keys and relation names.
     * @return array
     */
    abstract protected function relations();
}