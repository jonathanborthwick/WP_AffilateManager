<?php
// Check if accessed directly, exit if true for security
if (!defined('ABSPATH')) {
    exit;
}

// Include Stats Manager Class
$stats_manager = new AffiliateManager_StatsManager();
$link_manager = new AffiliateManager_AffiliateLinkManager();
$campaign_manager = new AffiliateManager_CampaignManager();

$total_links = count($link_manager->get_links());
$total_campaigns = count($campaign_manager->get_campaigns());

// Display dashboard title
?>
<div class="wrap">
    <h1><?php esc_html_e('Affiliate Manager Dashboard', 'affiliate-manager'); ?></h1>

    <!-- Quick Stats Section -->
    <div class="affiliate-dashboard-stats">
        <h2><?php esc_html_e('Quick Stats', 'affiliate-manager'); ?></h2>
        <ul>
            <li><?php esc_html_e('Total Affiliates:', 'affiliate-manager'); ?> <strong><?php echo esc_html($stats_manager->get_total_affiliates()); ?></strong></li>
            <li><?php esc_html_e('Total Links:', 'affiliate-manager'); ?> <strong><?php echo esc_html($stats_manager->get_total_links()); ?></strong></li>
            <li><?php esc_html_e('Total Campaigns:', 'affiliate-manager'); ?> <strong><?php echo esc_html($stats_manager->get_total_campaigns()); ?></strong></li>
            <li><?php esc_html_e('Total Earnings:', 'affiliate-manager'); ?> <strong><?php echo esc_html($stats_manager->get_total_earnings()); ?></strong></li>
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
                    <th><?php esc_html_e('Activity', 'affiliate-manager'); ?></th>
                    <th><?php esc_html_e('Amount', 'affiliate-manager'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch recent activity, assume you have a function for this
                $activities = $stats_manager->get_recent_affiliate_activities();

                if (!empty($activities)) {
                    foreach ($activities as $activity) {
                        ?>
                        <tr>
                            <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($activity->date))); ?></td>
                            <td><?php echo esc_html($activity->affiliate_name); ?></td>
                            <td><?php echo esc_html($activity->action); ?></td>
                            <td><?php echo esc_html(number_format($activity->amount, 2)); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4"><?php esc_html_e('No recent activity.', 'affiliate-manager'); ?></td>
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
            <li><a href="<?php echo admin_url('admin.php?page=affiliate_manager_settings'); ?>"><?php esc_html_e('Settings', 'affiliate-manager'); ?></a></li>
        </ul>
    </div>
</div>
