<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    use HasFactory;
    protected $table = 'product_materials';
    protected $fillable = ['product_id', 'material'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
