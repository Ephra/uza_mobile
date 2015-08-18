<?php
/**
 * karibu.com
 *
 * @author	Ephraim Swilla<swillae1@gmail.com>
 */
$user = !empty($user) ? $user : $ses_user;
?>
<?php
/**
 *   karibu.com
 * * @author Ephraim Swilla <swillae1@gmail.com>
 */
//css_media('bootstrap_calendar');
?>
<section class="vbox">
    <?php
    include_once 'modules/landing/banner/after_login.php';
    ?> 
    <section> 
        <section class="hbox stretch">
            <?php
            include_once 'modules/landing/aside.php';
            ?>
            <section id="content"  style="background: #f7f7f7;">
                <?php
                if (isset($_SESSION['pesasms']) && $_SESSION['pesasms'] == TRUE) {
                    include_once 'modules/pesaSMS/pesasms.php';
                } else {
                    include_once 'modules/profile/profile.php';
                }
                ?>
            </section> 
            <aside class="bg-light lter b-l aside-md hide" id="notes"> 
                <div class="wrapper">Notification</div> 
                <a href="#" onclick="$('#notes').remove()"><i class="fa fa-chevron-right"></i>close</a>
                <div id="ajax_load_all_notifications" style="overflow: scroll;"></div>
            </aside> 
        </section>
    </section>
</section>
<br/><br/><?php include_once 'modules/landing/footer.php'; ?>
<script>
    detect_username = function () {
        var username = '<?= $ses_user->username ?>';
        if (username == '') {
            $.get(url, {pg: 'register', section: 'detect_username'}, function (data) {
                $('#ajaxModal').remove();
                var $modal = $('<div class="modal" id="ajaxModal"></div>');
                $('body').append($modal);
                $modal.modal();
                $modal.html(data);
            });
        }
    };
    detect_number_validation = function () {
        var is_valid = '<?= $ses_user->phone_number_valid ?>';
        if (is_valid == 0) {
            $.get(url, {pg: 'register', section: 'detect_phone_valid', phone_number: '<?= $ses_user->phone_number ?>'}, function (data) {
                $('#ajaxModal').remove();
                var $modal = $('<div class="modal" id="ajaxModal"></div>');
                $('body').append($modal);
                $modal.modal();
                $modal.html(data);
            });
        }
    };
    $(document).ready(detect_username);
    $(document).ready(detect_number_validation);
</script>
<?php
$js_array = array(
    'charts/easypiechart/jquery.easy-pie-chart',
    'charts/sparkline/jquery.sparkline.min',
    'charts/flot/jquery.flot.min',
    'charts/flot/jquery.flot.tooltip.min',
    'charts/flot/jquery.flot.resize',
//'charts/flot/jquery.flot.grow',
// 'charts/flot/demo',
// 'calendar/bootstrap_calendar',
// 'calendar/demo',
// 'sortable/jquery.sortable'
);
foreach ($js_array as $js) {
    js_media($js);
}
?>