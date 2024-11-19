<?php
if (!defined('ABSPATH')) {
    exit;
}

$metrics_manager = new AffiliateManager_MetricsManager();
$metrics_summary = $metrics_manager->get_metrics_summary();
$recent_activity = $metrics_manager->get_recent_activity();
?>

<div class="wrap">
   <h1><?php esc_html_e('Affiliate Manager Dashboard', 'affiliate-manager'); ?></h1>

    <h2><?php esc_html_e('Quick Stats', 'affiliate-manager'); ?></h2>
    <p><?php esc_html_e('Total Links:', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_links'] ?? 0); ?></p>
    <p><?php esc_html_e('Total Campaigns:', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_campaigns'] ?? 0); ?></p>

    <h2><?php esc_html_e('Metrics Overview', 'affiliate-manager'); ?></h2>
    <p><?php esc_html_e('Total redirects (\'clicks\' off site):', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_clicks']); ?></p>
    <p>Coming soon: Total Conversions,Total Revenue, Total Cost, ROI and recent activity section</p>
<!--
    <p><?php esc_html_e('Total Conversions:', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_conversions']); ?></p>
    <p><?php esc_html_e('Total Revenue:', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_revenue']); ?></p>
    <p><?php esc_html_e('Total Cost:', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_cost']); ?></p>
    <p><?php esc_html_e('ROI:', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_roi']); ?>%</p>
-->
    <!--
    <h2><?php esc_html_e('Recent Activity', 'affiliate-manager'); ?></h2>
    <table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th><?php esc_html_e('Date', 'affiliate-manager'); ?></th>
            <th><?php esc_html_e('Affiliate', 'affiliate-manager'); ?></th>
            <th><?php esc_html_e('Source', 'affiliate-manager'); ?></th>
            <th><?php esc_html_e('Shortcode', 'affiliate-manager'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($recent_activity)) : ?>
            <?php foreach ($recent_activity as $activity) : ?>
                <tr>
                    <td><?php echo esc_html($activity->created_at); ?></td>
                    <td><?php echo esc_html($activity->link_name); ?></td>
                    <td><?php echo esc_html($activity->source); ?></td>
                    <td><?php echo esc_html($activity->short_code); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4"><?php esc_html_e('No recent activity.', 'affiliate-manager'); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
        -->
</div>
