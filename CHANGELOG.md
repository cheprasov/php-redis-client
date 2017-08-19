## CHANGELOG

### v1.7.2 (2017-08-19)
- Fixed bug of ClientFactory with default client version

### v1.7.0 (2017-07-22)
- The client was checked with Redis-4.0.0
- The client uses last stable redis version (Redis-4.0.0) by default.
- Deprecated client's methods: **executeRawString**, **parseRawString**
- Changed timeout on error **timeout_on_error_tryagain** from 0.25 sec to 0.05 sec.

### v1.6.1 (2017-02-04)
- Added check for empty data on reading response.
- Fixed some tests.

### v1.6.0 (2017-01-07)
- Added support for Redis 4.0 (the Client was tested with Redis 4.0 RC2).
- Added support for Redis Cluster.
- Added method **_syncClusterSlotsFromRedisServer** for RedisClient.
- Added command **SWAPDB**, **UNLINK**, **MEMORY** for Redis >= 4.0
- Updated command **FLUSHALL**, **FLUSHDB** for Redis >= 4.0

### v1.5.1 (2016-08-17)
- Fixed critical bug: https://github.com/cheprasov/php-redis-client/pull/45 Thanks to @BrianFranklin for help.

### v1.5.0 (2016-08-15)
- Added command **TOUCH** for Redis >= 3.2.1

### v1.4.0 (2016-07-18)
- You can choose default version of Redis Client (**ClientFactory::setDefaultRedisVersion**).
- Added parameter 'password' for config.
- Added parameter 'database' for config.

### v1.3.1 (2016-05-18)
- By default, the client works with the latest stable version of Redis (3.2.0).

### v1.3.0 (2016-05-07)
- Client was tested with Redis 3.2.0 (stable)
- Added command **BITFIELD** for Redis >= 3.2.
- Added **STORE** and **STOREDIST** params for **GEORADIUS** and **GEORADIUSBYMEMBER** for Redis >= 3.2.
- Added command **DEBUG HELP** for Redis >= 3.2.
- Changed some test for GEO.

### v1.2.3 (2016-05-02)
- Fixed command **PING** for Redis <= 2.6.
- Added common tests.

### v1.2.2 (2016-03-30)
- Fixed annotations and phpDocs.
- Fixed some tests.

### v1.2.1 (2016-03-04)
- Fixed some cluster commands (Redis >= 3.0): __CLUSTER COUNT-FAILURE-REPORTS__ and __CLUSTER SAVECONFIG__.

### v1.2.0 (2016-01-30)
- Updated command __MIGRATE__ (Redis >= 3.2): many fixes to the variable number of arguments (new) mode using the KEYS option.

### v1.1.0 (2016-01-26)
Sorry, no any history before.
