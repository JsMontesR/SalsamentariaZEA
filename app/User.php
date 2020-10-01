<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DateTimeInterface;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'di', 'celular', 'fijo', 'direccion', 'salario', 'rol_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rol()
    {
        return $this->belongsTo('App\Rol');
    }

    public function ventasRealizadas()
    {
        return $this->hasMany('App\Venta');
    }

    public function ventasQueLeRealizaron()
    {
        return $this->hasMany('App\Venta');
    }

    public function entradas()
    {
        return $this->hasMany('App\Entrada');
    }

    public function retiros()
    {
        return $this->hasMany('App\Retiro');
    }

    public function ingresos()
    {
        return $this->hasMany('App\Ingreso');
    }

    public function nominas()
    {
        return $this->hasMany('App\Nomina', 'empleado_id');
    }

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento', 'empleado_id');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
