<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapAttributesValueToCategory extends Model
{
    use HasFactory;
    protected $table = 'map_attributes_values_to_category';
    protected $fillable = [
        'id',
        'category_id',
        'attributes_value_id',
        'attributes_id'
    ];

    public function attributeValue()
    {
        return $this->belongsTo(Attribute_values::class, 'attributes_value_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
