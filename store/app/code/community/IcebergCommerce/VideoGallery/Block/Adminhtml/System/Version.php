<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @copyright  Copyright (c) 2011 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Block_Adminhtml_System_Version extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Render fieldset html
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
    	// Get Module Name
    	$class = get_class($this);
    	$parts = explode('_Block', $class);
    	$name    = count($parts) > 1 ? $parts[0] : 'UNKNOWN';
    	
    	// Get All Installed Modules
    	$modules = Mage::getConfig()->getNode('modules')->children();
    	
    	// Get version number for this module
    	$version = $modules->$name->version ? $modules->$name->version : '(Error) Could not find installed version number';

    	return '
    	<div class="entry-edit-head" style="color:#fff;font-weight:bold">
    	    Extension Information
    	</div>
    	<fieldset class="config">
    		<table class="form-list">
    			<tbody>
    				<tr>
    					<td class="label"><label>Version</label></td>
    					<td class="value">'.$version.'</td>
    				</tr>
    			</tbody>
    		</table>
    	</fieldset>';
    }
}
