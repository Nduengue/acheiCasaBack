<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    /* 
        Modelo
        Table property {
            id serial [primary key]
            user_id int [ref: > user.id]
            category_id enum("Praia", "Reserva", "Loja", "Terreno", "Residencial", "Escritorio", "Quartos","Armazem")
            agency_id int [ref: > agency.id, null]
            title varchar
            type varchar [null, note: "Casa, Apartamento, Armazem, Loja, Terreno, ..."]
            status varchar [note: "usado,novo etc"]
            type_of_business enum("A","V") [note: "A - Alugar  V - Venda"]
            furnished  enum("yes","no") [note: "Mobilada? Não"]
            country varchar
            address varchar
            city varchar
            province varchar
            location array [null, note: "[latitude & longitude]"]
            length decimal [null, note: "comprimento"]  
            width decimal [null, note: "largura"]
            description text
            room int [null]
            bathroom int [null]
            useful_sand decimal
            announces bool [default: false]
            favorite bool [default: false]
            deleted bool [default: false]
            time_unit enum("second","minute","hours","day","week","month","year") [null]
            minimum_time decimal [null]
            price decimal
        }
    */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'agency_id',
        'title',
        'type',
        'status',
        'type_of_business',
        'furnished',
        'country',
        'address',
        'city',
        'province',
        'location',
        'length',
        'width',
        'description',
        'room',
        'bathroom',
        'useful_sand',
        'announces',
        'favorite',
        'deleted',
        'time_unit',
        'minimum_time',
        'price',
    ];
    protected $hidden = [
        'announces',
    ];
    protected $casts = [
        'location' => 'array',
        'announces' => 'boolean',
        'deleted' => 'boolean',
    ];
    protected $attributes = [
        'deleted' => false,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function offer()
    {
        return $this->hasMany(Offer::class);
    }
    public function accommodationPhoto()
    {
        return $this->hasMany(AccommodationPhoto::class);
    }
    public function contact()
    {
        return $this->hasMany(Contact::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function checkPoint()
    {
        return $this->hasMany(CheckPoint::class);
    }
    public function business()
    {
        return $this->hasMany(Business::class);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
    public function like()
    {
        return $this->hasMany(Like::class);
    }
    public function getPhotoPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
