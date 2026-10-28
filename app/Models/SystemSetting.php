<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, static::$cache)) {
            return static::$cache[$key];
        }

        $setting = static::where('key', $key)->first();
        $val = $setting ? $setting->value : $default;
        static::$cache[$key] = $val;

        return $val;
    }

    public static function set(string $key, mixed $value): void
    {
        static::$cache[$key] = $value;
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string)$value]
        );
    }

    public static function clearCache(): void
    {
        static::$cache = [];
    }

    public static function competitionStatus(): string
    {
        return (string)static::get('competition_status', 'paused');
    }

    public static function activeMode(): string
    {
        return (string)(static::get('active_mode') ?: static::get('platform_mode', 'training'));
    }

    public static function isCompetitionActive(): bool
    {
        return static::activeMode() === 'competition' && static::competitionStatus() === 'active';
    }

    public static function isTrainingMode(): bool
    {
        return static::activeMode() === 'training';
    }
}

