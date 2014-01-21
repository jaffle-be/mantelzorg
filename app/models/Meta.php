<?php

class Meta extends Eloquent{

    protected $table = "meta";

    protected $fillable = array(
        'field', 'value', 'meta_type', 'meta_id'
    );

    public function meta()
    {
        return $this->morphTo();
    }

} 