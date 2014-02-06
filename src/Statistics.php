<?php
    class Statistics
    {
        public static function add($series, $key, $value)
        {
            $fields = array(
                'series' => $series,
                'key' => $key,
                'value' => $value
            );
            $GLOBALS['PW_DB']->executeInsert($fields, 'statistics');
        }

        public static function get($series)
        {
            $total = $GLOBALS['PW_DB']->executeRaw(Statistics::getSQL($series));
            $week = $GLOBALS['PW_DB']->executeRaw(Statistics::getSQL($series, ' AND `key`>' . (time() - 7*60*60*24)));
            $day = $GLOBALS['PW_DB']->executeRaw(Statistics::getSQL($series, ' AND `key`>' . (time() - 60*60*24)));
            return array($total[0], $week[0], $day[0]);
        }

        private static function getSQL($series, $suffix = '')
        {
            return 'SELECT COUNT(*) AS total, (SELECT COUNT(*) FROM statistics WHERE series=\'' .  $series . '\' AND
            value=1' . $suffix . ') AS online FROM statistics WHERE series=\'' . $series . '\'' . $suffix;
        }

        // NEW tck
        public static function getYearTimeline($series)
        {
            $startYear = date('Y') - 1;
            $startMonth = date('n') + 1;
            $startTime = mktime(0, 0, 0, $startMonth, 1, $startYear);
            
            $monthNames = array();
            for ($i = 0; $i < 12; $i++) {
                $monthNames[] = date('F', mktime(0, 0, 0, $startMonth + $i, 1, $startYear));
            }
            
            $month = array_fill(0, 12, 0);
            $hour = array_fill(0, 24, 0);
            $count = 0;
            $lastTime = 0;
            foreach ($GLOBALS['PW_DB']->executeRaw("
                SELECT `key`
                FROM statistics
                WHERE series = '$series'
                  AND value = 0
                  AND `key` > $startTime
            ") as $row) {
                if (date('Y', $row['key']) == $startYear) {
                    $month[date('n', $row['key']) - $startMonth]++;
                } else {
                    $month[12 - $startMonth + date('n', $row['key'])]++;
                }
                $hour[date('G', $row['key'])]++;
                $count++;
                $lastTime = max($lastTime, $row['key']);
            }

            return array($startTime, $lastTime, $count, $month, $hour, $monthNames);
        }
    }
?>
