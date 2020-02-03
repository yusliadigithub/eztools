<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;
use App\Modules\Admin\Models\MenuModel;
use App\Modules\Admin\Models\MasterStateModel;
use App\Modules\Admin\Models\MasterDistrictModel;
use App\Modules\Admin\Models\MasterCountryModel;
use App\Modules\Admin\Models\MasterGenderModel;
use App\Modules\Admin\Models\MasterTaxModel;

use Auth;
use DB;

class FormMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // month in malay format
        Form::macro('month', function($name='', $list = [], $selected = null, $options = [])
        {
            $list =  $list + [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Mac',
                4 => 'April',
                5 => 'Mei',
                6 => 'Jun',
                7 => 'Julai',
                8 => 'Ogos',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Disember',
            ];

            return Form::select($name, $list, $selected, $options);
        });

        // year 2000 till current date
        Form::macro('year', function($name, $list = [], $selected = null, $options = [])
        {

            foreach (range(date('Y'), 2000) as $year)
            {
                $years[$year] = $year;
            }

            $list =  $list + $years;

            return Form::select($name, $list, $selected, $options);
        });

        // disable, delete
        Form::macro('actions', function($name, $list = [], $selected = null, $options = []){

            $default = ['disable'=>__('Admin::user.disable'), 'delete'=>__('Admin::base.delete')];

            $list = $list + $default;

            return Form::select($name, $list, $selected, $options);
        });


        // master state
        Form::macro('state', function($name, $list=[], $selected = null, $options = []) {

            $lists = MasterStateModel::pluck('state_desc','state_id');

            return Form::select($name, $lists, $selected, $options);           

        });

        // master district
        Form::macro('district', function($name, $list=[], $selected = null, $options = []) {

            $lists = MasterDistrictModel::pluck('district_desc','district_id');

            return Form::select($name, $lists, $selected, $options);           

        });

        // master country
        Form::macro('country', function($name, $list=[], $selected = null, $options = []) {

            $lists = MasterCountryModel::pluck('country_desc','country_id');

            return Form::select($name, $lists, $selected, $options);           

        });

        // master gender
        Form::macro('gender_id', function($name, $list=[], $selected = null, $options = []) {

            $lists = MasterCountryModel::pluck('gender_desc','gender_id');

            return Form::select($name, $lists, $selected, $options);           

        });

        // master tax
        Form::macro('taxsupply', function($name, $list=[], $selected = null, $options = []) {

            $lists = MasterTaxModel::where('tax_type','1')->pluck('tax_code','tax_id');

            return Form::select($name, $lists, $selected, $options);           

        });

        // master tax
        Form::macro('taxpurchase', function($name, $list=[], $selected = null, $options = []) {

            $lists = MasterTaxModel::where('tax_type','2')->pluck('tax_code','tax_id');

            return Form::select($name, $lists, $selected, $options);           

        });

        // main menu/navigation
        Form::macro('main_menu', function() {

            foreach (MenuModel::tree() as $menu) :
              if( Auth::user()->can( $menu->menu_url ) ) :
                if(count($menu['children']) == 0) : 
                    $parent=route($menu->menu_url); 
                    $arrow = '';
                else: 
                    $parent='#'; 
                    $arrow = '<i class="fa fa-angle-left pull-right"></i>';
                endif;
                echo '<li class="treeview"><a href="'.$parent.'"><i class="'.$menu->menu_icon.'"></i> <span>'. __($menu->menu_trans).'</span>'.$arrow.'</a>';
                if(count($menu['children']) > 0) :
                echo '<ul class="treeview-menu">';
                    foreach ($menu['children'] as $child) :
                        if($child->menu_status==1):
                            if( Auth::user()->can( $child->menu_url ) ) :
                            echo '<li><a href="'.route($child->menu_url).'"><i class="'.$child->menu_icon.'"></i> '. __($child->menu_trans). '</a></li>';
                            endif;
                        endif;
                    endforeach;
                echo '</ul>';
                endif;
              echo '</li>';
              endif;
            endforeach;

        });


        // gender 
        Form::macro('gender', function($name, $list=[], $selected = null, $options = []) {

            $lists = ['M'=>'Male', 'F'=>'Female'];
            $arr = $list + $lists;

            return Form::select($name, $arr, $selected, $options);           

        });

        // phone type 
        Form::macro('phone_type', function($name, $list=[], $selected = null, $options = []) {

            $lists = ['HOME'=> __('Frontend::frontend.home'), 'OFFICE'=> __('Frontend::frontend.office'), 'WORK'=> __('Frontend::frontend.work'), 'PERSONAL'=> __('Frontend::frontend.personal') ];
            $arr = $list + $lists;

            return Form::select($name, $arr, $selected, $options);           

        });

        // address type 
        Form::macro('address_type', function($name, $list=[], $selected = null, $options = []) {

            $lists = ['SHIPPING'=> 'SHIPPING', 'BILLING'=> 'BILLING'];
            $arr = $list + $lists;

            return Form::select($name, $arr, $selected, $options);           

        });

        // select language - akan kena tambah disini kalau ada language baru
        Form::macro('language_select', function($name, $list=[], $selected = null, $options = [], $style='dropdown', $routename='') {

            $languages = $list + [  'en' => __('Admin::base.en'),
                                    'my' => __('Admin::base.my'),
                                 ];
            if($style == 'dropdown'):

                return Form::select($name, $languages, $selected, $options);
                
            else:
                
                $loop = '';
                foreach($languages as $key => $language) {
                    $loop .= '<li><a href="'.route($routename, $key).'">'. __('Admin::base.'.$key).'</a></li>';
                }

               echo '<li role="" class="dropdown">
                  <a class="text-uppercase" dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="javascript:;"><i class="fa fa-language"></i>'. $selected.'</a>
                  <ul class="dropdown-menu" role="menu">'.$loop.
                  '</ul>
                </li>';
                
            endif;
        });


        // SMTP encryption (SSL or TLS)
        Form::macro('smtp_encryption', function($name, $list=[], $selected = null, $options=[]) {

            $lists = $list + ['ssl' => 'SSL (secured)', 'tls' => 'TLS - (non secured)'];
            return Form::select($name, $lists, $selected, $options);
        });
        // dropdown for star
        Form::macro('users', function($name, $list=[], $selected = null, $options=[]) {

            $a = DB::table('users')->pluck('name', 'name')->toArray();
            $lists = $list + $a;
            return Form::select($name, $lists, $selected, $options);

        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
