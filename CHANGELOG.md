## CHANGELOG

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
