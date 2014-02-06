<?php

$monitor = Monitor::fetch(intval($_GET['id']));

list($startTime, $lastTime, $count, $month, $hour, $monthNames) = Statistics::getYearTimeline('monitor' . $monitor->getId());

?>

<script type="text/javascript" src="js/Chart.js/Chart.js"></script>

<div class="chart">
    <h1><?php $monitor->getAlias() ? p($monitor->getAlias() . ' - ') : ''; ?><?php p($monitor->getHostname()); ?>:<?php p($monitor->getPort()); ?></h1>
    
    <h2>Statistic since <?php echo date('F, Y', $startTime); ?></h2>
    <p>
<?php if (!$count) { ?>
        Monitor has no offline times.
<?php } else { ?>
        Monitor was <?php echo $count; ?> times offline - Last time: <?php p(GuiHelpers::formatDateLong($lastTime)); ?>.
<?php } ?>
    </p>
    
    <h2>Offline in time-line-view</h2>
    <canvas id="line-chart" width="605" height="200"></canvas>
    
    <h2>Offline in hour-radar-view</h2>
    <canvas id="radar-chart" width="605" height="400"></canvas>
</div>

<script type="text/javascript">
    
var line_ctx = document.getElementById("line-chart").getContext("2d");
var line_options = {};
var line_data = {
    labels : <?php echo json_encode($monthNames); ?>,
    datasets : [
        {
            fillColor : "rgba(180,180,180,0.5)",
            strokeColor : "rgba(180,180,180,1)",
            pointColor : "rgba(180,180,180,1)",
            pointStrokeColor : "#fff",
            data : <?php echo json_encode($month); ?>
        }
    ]
};
new Chart(line_ctx).Line(line_data, line_options);

var radar_ctx = document.getElementById("radar-chart").getContext("2d");
var radar_options = {};
var radar_data = {
    labels : ["00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23"],
    datasets : [
        {
            fillColor : "rgba(180,180,180,0.5)",
            strokeColor : "rgba(180,180,180,1)",
            pointColor : "rgba(180,180,180,1)",
            pointStrokeColor : "#fff",
            data : <?php echo json_encode($hour); ?>
        }
    ]
};
new Chart(radar_ctx).Radar(radar_data, radar_options);

</script>