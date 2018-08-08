<div id="ep-sidebar">
    <div id="ep-sidebar-accordion">
        <?php
        $tabGroups = $this->template['tab-groups'];
        $tabs      = $this->template['tabs'];
        $gcnt = 1;

        foreach($tabGroups as $groupKey => $group)
        {
            $tabsRef = $group['tabs'];
            $activeGroupClass = $gcnt == 1 ? 'class="active"' : '';
        ?>
            <h3><a href="#<?php echo esc_url($group['text']); ?>" <?php echo $activeGroupClass; ?>><span class="dashicons <?php echo esc_attr($group['icon']); ?>"></span><?php echo esc_html($group['text']); ?></a></h3>
            <div>
                <ul class="ep-tab">
        <?php
                foreach($tabsRef as $tabKey)
                {
                    $tab = $tabs[$tabKey];
                    $tcnt = 1;
                    $activeTabClass = $tcnt == 1 && $gcnt == 1? 'class="active"' : '';
                    ?>
                    <li><a href="#<?php echo esc_attr($tab['panel']); ?>" <?php echo $activeTabClass; ?>><?php echo esc_html($tab['text']); ?></a></li>
                    <?php
                    $tcnt++;
                }
        ?>
                </ul>
            </div>
        <?php
            $gcnt++;
        }
        ?>


    </div>
</div>