<?php

namespace RedisClient\Command\Traits;

trait AllCommandsTrait {
    use ConnectionCommandsTrait;
    use HashesCommandsTrait;
    use KeysCommandsTrait;
    use ListsCommandsTrait;
    use ScriptingCommandsTrait;
    use ServerCommandsTrait;
    use SetsCommandsTrait;
    use SortedSetsCommandsTrait;
    use StringsCommandsTrait;
    use TransactionsCommandsTrait;
}
