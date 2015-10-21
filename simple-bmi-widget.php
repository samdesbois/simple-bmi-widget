<?php
class Simple_Bmi_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct('simple-bmi-widget', 'Simple BMI widget', array('description' => 'Calculate your BMI.'));
	}

	public function form($instance)
	{
		// $title = isset($instance['title']) ? $instance['title'] : ''; // Opérateur ternaire
		$defauts = array(
			'title' => __('BMI Calculator'),
			'unity'=> 'us'
		);

		$instance = wp_parse_args($instance, $defauts);
		?>
		<!-- Le titre.-->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo _e('Title:');  ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo $instance['title'] ?>" placeholder="Exemple : Calcule IMC"/>
		</p>
		<!-- Maintenant les cases à cocher.-->
		<p>
			<?php _e('Unity','simple-bmi-widget'); ?>
			<br />
			<input type="radio" name="<?php echo $this->get_field_name('unity'); ?>" value="us" id="<?php echo $this->get_field_id('unity'); ?>" <?php if($instance['unity'] == 'us') {echo 'checked="checked"';} ?>/><label for="<?php echo $this->get_field_id('unity'); ?>"><?php echo 'lb/in'; ?></label>		
			<input type="radio" name="<?php echo $this->get_field_name('unity'); ?>" value="eu" id="<?php echo $this->get_field_id('unity'); ?>" <?php if($instance['unity'] == 'eu') {echo 'checked="checked"';} ?>/><label for="<?php echo $this->get_field_id('unity'); ?>" ><?php echo 'kg/m'; ?></label>
		</p>
		<?php
	}

	public function imc_form($unity, $int)
	{
		if ($int == 0)
		{
			_e('Datas written are certainly in a wrong format, please try again.', 'simple-bmi-widget');
		}
		?>
		<form action="" method="post">
			<p>
				<label for="imc_widget_poids"><?php _e( 'Weight', 'simple-bmi-widget'); ?> (<?php if ($unity == 'us') {echo 'lb';} else {echo 'kg';} ?>) :</label>
				<input id="imc_widget_poids" name="imc_widget_poids" />
				<br />
				<label for="imc_widget_taille"><?php _e( 'Height', 'simple-bmi-widget'); ?> (<?php if ($unity == 'us') {echo 'in';} else {echo 'm';} ?>) :</label>
				<input id="imc_widget_taille" name="imc_widget_taille" />
			</p>
		<input type="submit" value="<?php _e('Process', 'simple-bmi-widget'); ?>"/>
		<input type="reset" value="<?php _e('Erase', 'simple-bmi-widget'); ?>"/>
		</form>
		<?php
	}

	public function imc_analyze($int)
	{
		if ($int<15.5) {
			return __('severely underweight', 'simple-bmi-widget');
		} elseif($int<18.5) {
			return __('underweight', 'simple-bmi-widget');
		} elseif($int<25) {
			return __('normal', 'simple-bmi-widget');
		} elseif($int<30) {
			return __('overweight', 'simple-bmi-widget');
		} elseif($int<35) {
			return __('moderately obese', 'simple-bmi-widget');
		} elseif($int<40) {
			return __('severely obese', 'simple-bmi-widget');
		}	else {
			return __('very severely obsese', 'simple-bmi-widget');
		}
	}	

	public function imc_results($unity)
	{

		$k = ($unity == 'eu') ? 1 : 703;
		$weight = preg_match('#,#', $_POST['imc_widget_poids']) ? preg_replace('#([0-9])(,)([0-9])#', '$1.$3', $_POST['imc_widget_poids']) : $_POST['imc_widget_poids'];
		$height = preg_match('#,#', $_POST['imc_widget_taille']) ? preg_replace('#([0-9])(,)([0-9])#', '$1.$3', $_POST['imc_widget_taille']) : $_POST['imc_widget_taille'];
		$imc = $k*$weight/pow($height,2); 
		$imc = number_format($imc, 2);
		?>
			<p>
			IMC : <?php echo $imc; ?> kg/m<sup>2</sup>
			<br />
			<?php echo __('Status: ', 'simple-bmi-widget').$this->imc_analyze($imc); ?>
			</p>
			<form method="post" action="">
			<input id="imc_widget_poids" name="imc_widget_poids" type="hidden" value="" />
			<input id="imc_widget_taille" name="imc_widget_taille" type="hidden" value="" />
			<input type="submit" value="<?php _e('Restart', 'simple-bmi-widget'); ?>" />
			</form>
		<?php
	}
	
	public function widget($args, $instance)
	{

		echo $args['before_widget'];
		echo $args['before_title'];
		echo apply_filters('widget_title', $instance['title']);
		echo $args['after_title'];
	
		$unity = $instance['unity'];

		if (isset($_POST['imc_widget_poids']) && !empty($_POST['imc_widget_poids']) && preg_match('#^[0-9]{1,3}[\.,]?[0-9]{0,3}$#',$_POST['imc_widget_poids']) && isset($_POST['imc_widget_taille']) && !empty($_POST['imc_widget_taille']) && preg_match('#^[0-9]{1,3}[\.,]?[0-9]{0,3}$#',$_POST['imc_widget_taille']))
		{
			$this->imc_results($unity);
		} elseif ((isset($_POST['imc_widget_poids']) and !empty($_POST['imc_widget_poids'])) or (isset($_POST['imc_widget_taille']) and !empty($_POST['imc_widget_poids'])))
		{
			$this->imc_form($unity, 0);
		} else
		{
			$this->imc_form($unity, 1);
		}

		echo $args['after_widget'];
	}


}
