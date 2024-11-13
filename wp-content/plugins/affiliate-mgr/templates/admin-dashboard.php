<?php
// Check if accessed directly, exit if true for security
if (!defined('ABSPATH')) {
    exit;
}

// Include Stats Manager Class
$stats_manager = new AffiliateManager_StatsManager();
$link_manager = new AffiliateManager_AffiliateLinkManager();
$campaign_manager = new AffiliateManager_CampaignManager();
$metrics_manager = new AffiliateManager_MetricsManager();

$total_links = count($link_manager->get_links());
$total_campaigns = count($campaign_manager->get_campaigns());
$metrics = $metrics_manager->get_metrics_summary();

// Display dashboard title
?>
<div class="wrap">
    <h1><?php esc_html_e('Affiliate Manager Dashboard', 'affiliate-manager'); ?></h1>

    <!-- Quick Stats Section -->
    <div class="affiliate-dashboard-stats">
        <h2><?php esc_html_e('Quick Stats', 'affiliate-manager'); ?></h2>
        <ul>
            <li><?php esc_html_e('Total Links:', 'affiliate-manager'); ?> <strong><?php echo esc_html($stats_manager->get_total_links()); ?></strong></li>
            <li><?php esc_html_e('Total Campaigns:', 'affiliate-manager'); ?> <strong><?php echo esc_html($stats_manager->get_total_campaigns()); ?></strong></li>
        </ul>
    </div>

        <!-- Metrics Overview Section -->
    <div class="affiliate-dashboard-metrics">
        <h2><?php esc_html_e('Metrics Overview', 'affiliate-manager'); ?></h2>
        <ul>
            <li><?php esc_html_e('Total Clicks:', 'affiliate-manager'); ?> <strong><?php echo esc_html($metrics['total_clicks']); ?></strong></li>
            <li><?php esc_html_e('Total Conversions:', 'affiliate-manager'); ?> <strong><?php echo esc_html($metrics['total_conversions']); ?></strong></li>
            <li><?php esc_html_e('Total Revenue:', 'affiliate-manager'); ?> <strong><?php echo esc_html(number_format($metrics['total_revenue'], 2)); ?></strong></li>
            <li><?php esc_html_e('Total Cost:', 'affiliate-manager'); ?> <strong><?php echo esc_html(number_format($metrics['total_cost'], 2)); ?></strong></li>
            <li><?php esc_html_e('ROI:', 'affiliate-manager'); ?> <strong><?php echo esc_html(number_format($metrics['total_roi'], 2)); ?>%</strong></li>
        </ul>
    </div>

    <!-- Recent Activity Section -->
    <div class="affiliate-dashboard-recent-activity">
        <h2><?php esc_html_e('Recent Activity', 'affiliate-manager'); ?></h2>
        <table class="widefat fixed">
            <thead>
                <tr>
                    <th><?php esc_html_e('Date', 'affiliate-manager'); ?></th>
                    <th><?php esc_html_e('Affiliate', 'affiliate-manager'); ?></th>
                    <th><?php esc_html_e('Campaign', 'affiliate-manager'); ?></th>
                    <th><?php esc_html_e('Source', 'affiliate-manager'); ?></th>
                    <th><?php esc_html_e('Shortcode', 'affiliate-manager'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch recent activity
                $activities = $stats_manager->get_recent_affiliate_activities();

                if (!empty($activities)) {
                    foreach ($activities as $activity) {
                        ?>
                        <tr>
                            <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($activity->date))); ?></td>
                            <td><?php echo esc_html($activity->short_code); ?></td>
                            <td><?php echo esc_html($activity->campaign_name); ?></td>
                            <td><?php echo esc_html($activity->source); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5"><?php esc_html_e('No recent activity.', 'affiliate-manager'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Quick Links Section -->
    <div class="affiliate-dashboard-links">
        <h2><?php esc_html_e('Quick Links', 'affiliate-manager'); ?></h2>
        <ul>
            <li><a href="<?php echo admin_url('admin.php?page=affiliate_manager_links'); ?>"><?php esc_html_e('Manage Links', 'affiliate-manager'); ?></a></li>
            <li><a href="<?php echo admin_url('admin.php?page=affiliate_manager_campaigns'); ?>"><?php esc_html_e('Manage Campaigns', 'affiliate-manager'); ?></a></li>
            <li><a href="<?php echo admin_url('admin.php?page=affiliate_manager_categories'); ?>"><?php esc_html_e('Manage Categories', 'affiliate-manager'); ?></a></li>
        </ul>
    </div>
</div>
