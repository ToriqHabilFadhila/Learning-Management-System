<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    const CACHE_TTL = 86400; // 24 hours
    const CACHE_TTL_SHORT = 3600; // 1 hour
    const CACHE_TTL_MEDIUM = 21600; // 6 hours

    /**
     * Get AI recommendations from cache or generate new ones
     */
    public static function getOrGenerateRecommendations($userId, $classIds, $generator)
    {
        $cacheKey = self::getRecommendationsCacheKey($userId, $classIds);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($generator, $cacheKey) {
            Log::info('Generating new AI recommendations', ['cache_key' => $cacheKey]);
            return $generator();
        });
    }

    /**
     * Get student profile from cache or generate new one
     */
    public static function getOrGenerateProfile($userId, $classIds, $generator)
    {
        $cacheKey = self::getProfileCacheKey($userId, $classIds);
        
        return Cache::remember($cacheKey, self::CACHE_TTL_MEDIUM, function () use ($generator, $cacheKey) {
            Log::info('Generating new student profile', ['cache_key' => $cacheKey]);
            return $generator();
        });
    }

    /**
     * Get feedback from cache or fetch from database
     */
    public static function getOrFetchFeedback($userId, $classIds, $fetcher)
    {
        $cacheKey = self::getFeedbackCacheKey($userId, $classIds);
        
        return Cache::remember($cacheKey, self::CACHE_TTL_MEDIUM, function () use ($fetcher, $cacheKey) {
            Log::info('Fetching feedback from database', ['cache_key' => $cacheKey]);
            return $fetcher();
        });
    }

    /**
     * Get class materials from cache
     */
    public static function getOrFetchMaterials($classIds, $fetcher)
    {
        $cacheKey = self::getMaterialsCacheKey($classIds);
        
        return Cache::remember($cacheKey, self::CACHE_TTL_SHORT, function () use ($fetcher, $cacheKey) {
            Log::info('Fetching materials from database', ['cache_key' => $cacheKey]);
            return $fetcher();
        });
    }

    /**
     * Clear recommendations cache for a specific user
     */
    public static function clearRecommendationsCache($userId)
    {
        $pattern = "recommendations_{$userId}_*";
        self::invalidateByPattern($pattern);
        
        // Also clear related caches
        $profilePattern = "profile_{$userId}_*";
        self::invalidateByPattern($profilePattern);
        
        $feedbackPattern = "feedback_{$userId}_*";
        self::invalidateByPattern($feedbackPattern);
        
        Log::info('Cleared recommendations cache for user', ['user_id' => $userId]);
    }

    /**
     * Invalidate user recommendations cache
     */
    public static function invalidateUserRecommendations($userId)
    {
        // Invalidate all recommendation caches for this user
        $pattern = "lms_recommendations_{$userId}_*";
        self::invalidateByPattern($pattern);
        Log::info('Invalidated user recommendations cache', ['user_id' => $userId]);
    }

    /**
     * Invalidate class materials cache
     */
    public static function invalidateClassMaterials($classId)
    {
        $cacheKey = self::getMaterialsCacheKey([$classId]);
        Cache::forget($cacheKey);
        Log::info('Invalidated class materials cache', ['class_id' => $classId]);
    }

    /**
     * Invalidate all feedback cache for a user
     */
    public static function invalidateUserFeedback($userId)
    {
        $pattern = "lms_feedback_{$userId}_*";
        self::invalidateByPattern($pattern);
        Log::info('Invalidated user feedback cache', ['user_id' => $userId]);
    }

    /**
     * Invalidate cache by pattern (Redis only)
     */
    private static function invalidateByPattern($pattern)
    {
        try {
            // Check if using Redis driver
            if (config('cache.default') === 'redis') {
                $redis = Cache::getRedis();
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    foreach ($keys as $key) {
                        Cache::forget($key);
                    }
                    Log::info('Invalidated cache by pattern', ['pattern' => $pattern, 'count' => count($keys)]);
                }
            } else {
                // For non-Redis drivers, just forget the specific key
                Cache::forget($pattern);
                Log::info('Invalidated cache key', ['key' => $pattern]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to invalidate cache by pattern', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate cache key for recommendations
     */
    private static function getRecommendationsCacheKey($userId, $classIds)
    {
        $classIdString = implode('_', is_array($classIds) ? $classIds : $classIds->toArray());
        return "recommendations_{$userId}_{$classIdString}";
    }

    /**
     * Generate cache key for student profile
     */
    private static function getProfileCacheKey($userId, $classIds)
    {
        $classIdString = implode('_', is_array($classIds) ? $classIds : $classIds->toArray());
        return "profile_{$userId}_{$classIdString}";
    }

    /**
     * Generate cache key for feedback
     */
    private static function getFeedbackCacheKey($userId, $classIds)
    {
        $classIdString = implode('_', is_array($classIds) ? $classIds : $classIds->toArray());
        return "feedback_{$userId}_{$classIdString}";
    }

    /**
     * Generate cache key for materials
     */
    private static function getMaterialsCacheKey($classIds)
    {
        $classIdString = implode('_', is_array($classIds) ? $classIds : $classIds->toArray());
        return "materials_{$classIdString}";
    }

    /**
     * Clear all LMS cache
     */
    public static function clearAll()
    {
        try {
            // Check if using Redis driver
            if (config('cache.default') === 'redis') {
                $redis = Cache::getRedis();
                $keys = $redis->keys('lms_*');
                
                if (!empty($keys)) {
                    foreach ($keys as $key) {
                        Cache::forget($key);
                    }
                    Log::info('Cleared all LMS cache', ['count' => count($keys)]);
                }
            } else {
                // For non-Redis drivers, use flush
                Cache::flush();
                Log::info('Flushed all cache');
            }
        } catch (\Exception $e) {
            Log::warning('Failed to clear all cache', ['error' => $e->getMessage()]);
        }
    }
}
