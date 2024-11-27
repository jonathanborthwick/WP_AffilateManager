<?php
if (!defined('ABSPATH')) {
    exit;
}

$metrics_manager = new AffiliateManager_MetricsManager();
$metrics_summary = $metrics_manager->get_metrics_summary();
$recent_activity = $metrics_manager->get_recent_activity();
$networksManager = new AffiliateManager_NetworksManager();
$activeNetworks = $networksManager->get_active_networks();
?>

<div class="wrap">
   <h1><?php esc_html_e('Affiliate Manager Dashboard', 'affiliate-manager'); ?></h1>

    <h2><?php esc_html_e('Quick Stats', 'affiliate-manager'); ?></h2>
    <p><?php esc_html_e('Total Links:', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_links'] ?? 0); ?></p>
    <p><?php esc_html_e('Total Campaigns:', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_campaigns'] ?? 0); ?></p>

    <h2><?php esc_html_e('Metrics Overview', 'affiliate-manager'); ?></h2>
    <p><?php esc_html_e('Total redirects (\'clicks\' off site):', 'affiliate-manager'); ?> <?php echo esc_html($metrics_summary['total_clicks']); ?></p>
    
    
    <h2><?php esc_html_e('Recent Activity', 'affiliate-manager'); ?></h2>

    <div class="tab-container">
        <?php if (!empty($activeNetworks)) : ?>
            <?php 
                $index = 0;
                foreach ($activeNetworks as $network) : ?>
        <div class="tab <?php if($index === 0) echo("active"); ?>" data-tab="<?php echo esc_html($network->network_name) ?>" onclick="am.dashboard.showTab('<?php echo esc_html($network->network_name) ?>')"><?php echo esc_html(ucfirst($network->network_name)) ?></div>
            <?php $index = $index+1;
               endforeach; 
            ?>
            <?php endif; ?>
    </div>

    <?php $index = 0; ?>
    <?php foreach ($activeNetworks as $network) : ?>
    <div id="<?php echo esc_html($network->network_name) ?>" class="tab-content <?php if($index === 0) echo("active"); ?>">
        <label><?php echo esc_html(ucfirst($network->network_name)) ?> Activity</label>  
        <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php esc_html_e('Created Date', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Shortcode', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Campaign', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Category', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Hits', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Hit Date', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('Source', 'affiliate-manager'); ?></th>
                <th><?php esc_html_e('QR Code', 'affiliate-manager'); ?></th>
            </tr>
        </thead>
        <tbody>
    <?php if (!empty($recent_activity)) : ?>
        <?php foreach ($recent_activity as $activity) : ?>
            <tr>
                    <td><?php echo esc_html($activity->created_at) ?? ""; ?></td>
                    <td><?php echo esc_html($activity->short_code) ?? ""; ?></td>
                    <td><?php echo esc_html($activity->campaign) ?? ""; ?></td>
                    <td><?php echo esc_html($activity->category) ?? ""; ?></td>
                    <td><?php echo esc_html($activity->hits) ?? ""; ?></td>
                    <td><?php echo esc_html($activity->hit_date) ?? ""; ?></td>
                    <td><?php echo esc_html($activity->hit_source) ?? ""; ?></td>
                    <td><img src="..\assets\images\qrCodePlaceholder.png" alt="QR Code" width="50"></td><!-- todo actual qr code-->
                </tr>
        <?php endforeach;?>
        <?php else : ?>
            <tr>
                <td colspan="4"><?php esc_html_e('No recent activity.', 'affiliate-manager'); ?></td>
            </tr>
    <?php endif; ?>
        </tbody>
    </div>
        <?php $index = $index+1; ?>
    <? endforeach; ?>  

</div>
