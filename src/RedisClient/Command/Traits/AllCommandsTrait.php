<?php

namespace RedisClient\Command\Traits;

trait AllCommandsTrait {
    use ClusterCommandsTrait;
    use ConnectionCommandsTrait;
    use HashesCommandsTrait;
    use HyperLogLogCommandsTrait;
    use KeysCommandsTrait;
    use ListsCommandsTrait;
    use ScriptingCommandsTrait;
    use ServerCommandsTrait;
    use SetsCommandsTrait;
    use SortedSetsCommandsTrait;
    use StringsCommandsTrait;
    use TransactionsCommandsTrait;
}
