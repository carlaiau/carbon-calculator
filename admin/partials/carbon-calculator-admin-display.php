 <?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Carbon_Calculator
 * @subpackage Carbon_Calculator/admin/partials
 */

?>
<div class="wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php">
        <?php
            $options = get_option($this->plugin_name);
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
            foreach($this->generators as $g){ ?>
            <div class="carbon-form-container">
                <h3><?php echo ucfirst($g); ?></h3>
                <table class="form-table">
                    <tr>
                        <th><?php esc_attr_e('Overall Text', $this->plugin_name); ?></th>
                        <td>
                        <input type="text"
                        id="<?php echo $this->plugin_name; ?>-<?php echo $g; ?>-free_text_overall"
                        name="<?php echo $this->plugin_name; ?>[<?php echo $g; ?>-free_text_overall]"
                        value="<?php if(!empty($options[$g .'-free_text_overall'])) echo $options[$g .'-free_text_overall']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_attr_e('Since Viewing Text', $this->plugin_name); ?></th>
                        <td>
                        <input type="text"
                        id="<?php echo $this->plugin_name; ?>-<?php echo $g; ?>-free_text_current"
                        name="<?php echo $this->plugin_name; ?>[<?php echo $g; ?>-free_text_current]"
                        value="<?php if(!empty($options[$g . '-free_text_current'])) echo $options[$g .'-free_text_current']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_attr_e('Date', $this->plugin_name); ?></th>
                        <td>
                            <input type="date"
                            id="<?php echo $this->plugin_name; ?>-<?php echo $g; ?>-initial_date"
                            name="<?php echo $this->plugin_name; ?>[<?php echo $g; ?>-initial_date]"
                            value="<?php if(!empty($options[$g .'-initial_date'])) echo $options[$g . '-initial_date']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_attr_e('Intial', $this->plugin_name); ?></th>
                        <td>
                            <input type="text"
                            id="<?php echo $this->plugin_name; ?>-<?php echo $g; ?>-initial_savings"
                            name="<?php echo $this->plugin_name; ?>[<?php echo $g; ?>-initial_savings]"
                            value="<?php if(!empty($options[$g .'-initial_savings'])) echo $options[$g .'-initial_savings']; ?>"
                            />
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_attr_e('Yearly', $this->plugin_name); ?></th>
                        <td>
                            <input type="text"
                            id="<?php echo $this->plugin_name; ?>-<?php echo $g; ?>-yearly_savings"
                            name="<?php echo $this->plugin_name; ?>[<?php echo $g; ?>-yearly_savings]"
                            value="<?php if(!empty($options[$g .'-yearly_savings'])) echo $options[$g .'-yearly_savings']; ?>"
                            />
                        </td>
                    </tr>
                </table>
                <div class="carbon-output">
                    <?php
                    $this->output_each_generation($g, $options[$g . '-initial_date'], $options[$g . '-yearly_savings'],
                        $options[$g . '-initial_savings'], $options[$g . '-free_text_overall'], $options[$g . '-free_text_current']);
                    ?>
                </div>
            </div>
                <?php } // end foreach ?>
        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
    </form>


</div>
