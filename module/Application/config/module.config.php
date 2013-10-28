<?php
namespace Application;
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type'=>'segment',
                'options' => array(
                    'route'    => '/[:id]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        		'resetpasswordall' => array(
        				'type' => 'Zend\Mvc\Router\Http\Literal',
        				'options' => array(
        						'route'    => '/resetpasswordall',
        						'defaults' => array(
        								'controller' => 'Application\Controller\Index',
        								'action'     => 'resetpasswordall',
        						),
        				),
        		),
        		'setpreferences' => array(
        				'type' => 'Zend\Mvc\Router\Http\Literal',
        				'options' => array(
        						'route'    => '/setpreferences',
        						'defaults' => array(
        								'controller' => 'Application\Controller\Index',
        								'action'     => 'setpreferences',
        						),
        				),
        		),
        		
        		'addlogtime' => array(
        				'type' => 'Zend\Mvc\Router\Http\Literal',
        				'options' => array(
        						'route'    => '/addlogtime',
        						'defaults' => array(
        								'controller' => 'Application\Controller\User',
        								'action'     => 'addlogtime',
        						),
        				),
        		),
        		'updatecreateddate' => array(
        				'type' => 'Zend\Mvc\Router\Http\Literal',
        				'options' => array(
        						'route'    => '/updatecreateddate',
        						'defaults' => array(
        								'controller' => 'Application\Controller\User',
        								'action'     => 'updatecreateddate',
        						),
        				),
        		),
        		 
        		'forgotpassword' => array(
        				'type' => 'Zend\Mvc\Router\Http\Literal',
        				'options' => array(
        						'route'    => '/forgotpassword',
        						'defaults' => array(
        								'controller' => 'Application\Controller\Index',
        								'action'     => 'forgotpassword',
        						),
        				),
        		),
        		'changepassword' => array(
        				'type' => 'Zend\Mvc\Router\Http\Literal',
        				'options' => array(
        						'route'    => '/changepassword',
        						'defaults' => array(
        								'controller' => 'Application\Controller\Index',
        								'action'     => 'changepassword',
        						),
        				),
        		),        		
			'login' => array(
        		'type' => 'Zend\Mvc\Router\Http\Literal',
        		'options' => array(
        			'route'    => '/login',
        			'defaults' => array(
        			'controller' => 'Application\Controller\Index',
        			'action'     => 'login',
        			),
        		),
        	),
        	
        	'logout' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/logout',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Index',
        							'action'     => 'logout',
        					),
        			),
        	),
        	'sessionexpired' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/sessionexpired',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Index',
        							'action'     => 'sessionexpired',
        					),
        			),
        	),
        	'loginreport' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/loginreport',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Index',
        							'action'     => 'loginreport',
        					),
        			),
        	),
        	'loginreminder' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/loginreminder',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Index',
        							'action'     => 'loginreminder',
        					),
        			),
        	),
        	'attendancereport' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/attendancereport',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'attendancereport',
        					),
        			),
        	),

        	'loginreportgrid' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/grid',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Index',
        							'action'     => 'grid',
        					),
        			),
        	),
        	'loginreportsubgrid' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/getlogindetailbyuser',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Index',
        							'action'     => 'getlogindetailbyuser',
        					),
        			),
        	),
        	
        	'deleteloginrow' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteloginrow',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Index',
        							'action'     => 'deleteloginrow',
        					),
        			),
        	),
        	
        	/*'activityhistory' => array(
                //'type' => 'Zend\Mvc\Router\Http\Literal',
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/activity-history[/:action][/:id]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Activityhistory',
                        'action'     => 'index',
                    ),
                ),
            ),*/
   //users     	
        	'report' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/report',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'index',
        					),
        			),
        	),
        	'weeklyreport' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/weeklyreport',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'weeklyreport',
        					),
        			),
        	),
        	'dailyreportstatus' => array(
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/dailyreportstatus[/:date]',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'dailyreportstatus',
        					),
        			),
        	),
        	 
        	'reportstatus' => array(
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/reportstatus',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'reportstatus',
        					),
        			),
        	),
        	
        	
        	'piechart' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/piechart',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'piechart',
        					),
        			),
        	),
        	
      
        	'userreport' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/userreport',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'userreport',
        					),
        			),
        	),
        	'summaryreport' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/summaryreport',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'summaryreport',
        					),
        			),
        	),
        	'reportbyproject' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/reportbyproject',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'reportbyproject',
        					),
        			),
        	),
        	 
        	'users' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/users',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'index',
        					),
        			),
        	),
        	'myprofile' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/myprofile',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'myprofile',
        					),
        			),
        	),
        	
        	'editcontact' => array(
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/editcontact/userid[/:id]',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'editcontact',
        					),
        			),
        	),
        	
        	'editmyprofile' => array(
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/editmyprofile',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'editcontact',
        					),
        			),
        	),
        	
        	'addcontact' => array(
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/addcontact',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'editcontact',
        					),
        			),
        	),
        	 
        	'getmultiuser' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/getmultiuser',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'getmultiuser',
        					),
        			),
        	),
        	'griduser' => array(
        				'type'=>'segment',
        			'options' => array(
        					'route'    => '/griduser',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'griduser',
        					),
        			),
        	),
        	
        	'adduser' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/adduser',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'adduser',
        					),
        			),
        	),
        	'deleteuser' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteuser',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'deleteuser',
        					),
        			),
        	),
        //activity log
        	'activitylog' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/activitylog',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activitylog',
        							'action'     => 'index',
        					),
        			),
        	),
        	'getactivity' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/getactivity',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activitylog',
        							'action'     => 'getactivity',
        					),
        			),
        	),
        	'logspenttime' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/logspenttime',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activitylog',
        							'action'     => 'logspenttime',
        					),
        			),
        	),
        	
        	'gridactivitylog' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/gridactivitylog[/:activityid]',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activitylog',
        							'action'     => 'gridactivitylog',
        					),
        			),
        	),
        	'addactivitylog' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addactivitylog',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activitylog',
        							'action'     => 'addactivitylog',
        					),
        			),
        	),
        	
        	'deleteactivitylog' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteactivitylog',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activitylog',
        							'action'     => 'deleteactivitylog',
        					),
        			),
        	),
        	 
         //Admincontroller       	
        	'admin' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/admin',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'index',
        					),
        			),
        	),
        	'company' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/company',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Company',
        							'action'     => 'index',
        					),
        			),
        	),
        	'gridcompany' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridcompany',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Company',
        							'action'     => 'gridcompany',
        					),
        			),
        	),
        	'userbycompany' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/userbycompany',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Company',
        							'action'     => 'userbycompany',
        					),
        			),
        	),
        	
        	'subgriduserbycompany' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/subgriduserbycompany',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Company',
        							'action'     => 'subgriduserbycompany',
        					),
        			),
        	),
        	'addcompany' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addcompany',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Company',
        							'action'     => 'addcompany',
        					),
        			),
        	),
        	'deletecompany' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deletecompany',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Company',
        							'action'     => 'deletecompany',
        					),
        			),
        	),
        	//holiday  	
        	'holiday' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/holiday',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'holiday',
        					),
        			),
        	),
        	'gridholiday' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridholiday',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'gridholiday',
        					),
        			),
        	),
        	'addholiday' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addholiday',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'addholiday',
        					),
        			),
        	),
        	'deleteholiday' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteholiday',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'deleteholiday',
        					),
        			),
        	),
  			//activity status      	
        	'activitystatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/activitystatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'activitystatuses',
        					),
        			),
        	),
        	'gridactivitystatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridactivitystatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'gridactivitystatuses',
        					),
        			),
        	),
        	'addactivitystatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addactivitystatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'addactivitystatuses',
        					),
        			),
        	),
        	'editactivitystatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/editactivitystatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'editactivitystatuses',
        					),
        			),
        	),
        	'deleteactivitystatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteactivitystatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'deleteactivitystatuses',
        					),
        			),
        	),
    		//activitycategories
        	'activitycategories' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/activitycategories',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'activitycategories',
        					),
        			),
        	),
        	'gridactivitycategories' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridactivitycategories',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'gridactivitycategories',
        					),
        			),
        	),
        	'addactivitycategories' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addactivitycategories',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'addactivitycategories',
        					),
        			),
        	),
        	'editactivitycategories' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/editactivitycategories',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'editactivitycategories',
        					),
        			),
        	),
        	'deleteactivitycategories' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteactivitycategories',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'deleteactivitycategories',
        					),
        			),
        	),
      		//projectstatuses  	
        	'projectstatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/projectstatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'projectstatuses',
        					),
        			),
        	),
        	'gridprojectstatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridprojectstatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'gridprojectstatuses',
        					),
        			),
        	),
        	'addprojectstatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addprojectstatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'addprojectstatuses',
        					),
        			),
        	),
        	'deleteprojectstatuses' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteprojectstatuses',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Admin',
        							'action'     => 'deleteprojectstatuses',
        					),
        			),
        	),
   			//projects
        	'projects' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/projects',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'index',
        					),
        			),
        	),
        	'gridprojects' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridprojects',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'gridprojects',
        					),
        			),
        	),
        	'getspenthoursbyproject' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/getspenthoursbyproject',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'getspenthoursbyproject',
        					),
        			),
        	),
        	'managemilestones' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/managemilestones',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'managemilestones',
        					),
        			),
        	),
        	//	 'activities
        	'sendmail' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/sendmail',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'sendmail',
        					),
        			),
        	),
        	'activities' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/activities',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'index',
        					),
        			),
        	),
        	//preferences...
        	'preferences' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/preferences',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'preferences',
        					),
        			),
        	),
        	'gridpreferences' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridpreferences',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'gridpreferences',
        					),
        			),
        	),
        	'addpreferences' => array(
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/addpreferences[/:id]',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'addpreferences',
        					),
        			),
        	),
        	'mypreferences' => array(
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/addpreferences[/userid/:userid]',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'addpreferences',
        					),
        			),
        	),
        	'deletepreferences' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deletepreferences',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'deletepreferences',
        					),
        			),
        	),
        	 
        	'addactivity' => array(
        			//'type' => 'Zend\Mvc\Router\Http\Literal',
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/addactivity[/:id]',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'addactivity',
        					),
        			),
        	),
        	'getmilestone' => array(
        			//'type' => 'Zend\Mvc\Router\Http\Literal',
        			'type'=>'segment',
        			'options' => array(
        					'route'    => '/getmilestone',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'getmilestone',
        					),
        			),
        	),
        	
        	'gridactivities' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridactivities',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'gridactivities',
        					),
        			),
        	),

        	'deleteactivity' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteactivity',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'deleteactivity',
        					),
        			),
        	),

        	'viewactivitydetail' => array(
        			//'type' => 'Zend\Mvc\Router\Http\Literal',
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/viewactivitydetail/:id',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'viewactivitydetail',
        					),
        			),
        	),
			//Project
        	'addprojects' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addprojects',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'addprojects',
        					),
        			),
        	),
        	'deleteprojects' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteprojects',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'deleteprojects',
        					),
        			),
        	),
        	'viewprojectdetail' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/viewprojectsdetail/:id',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'viewprojectsdetail',
        					),
        			),
        	),
        	 
        	'milestones' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/milestones',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'milestones',
        					),
        			),
        	),
        	'gridmilestones' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridmilestones',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'gridmilestones',
        					),
        			),
        	),
        	'addmilestones' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/addmilestones[/:id]',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'addmilestones',
        					),
        			),
        	),
        	'deletemilestones' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deletemilestones',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projects',
        							'action'     => 'deletemilestones',
        					),
        			),
        	),        	
        	//Projet type
        	'projecttype' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/projecttype',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projecttype',
        							'action'     => 'index',
        					),
        			),
        	),
        	'gridprojecttype' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridprojecttype',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projecttype',
        							'action'     => 'gridprojecttype',
        					),
        			),
        	),
        	'addprojecttype' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addprojecttype',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projecttype',
        							'action'     => 'addprojecttype',
        					),
        			),
        	),
        	'deleteprojecttype' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteprojecttype',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Projecttype',
        							'action'     => 'deleteprojecttype',
        					),
        			),
        	),
        	'updateduedate' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/updateduedate',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Activities',
        							'action'     => 'updateduedate',
        					),
        			),
        	),
        	
        	'leave' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/leave',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Leaves',
        							'action'     => 'index',
        					),
        			),
        	),
        	'approveleave' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/leave/approveleave/:flag/:id',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Leaves',
        							'action'     => 'approveleave',
        					),
        			),
        	),
        	'leavelist' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/leave/list',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Leaves',
        							'action'     => 'leavelist',
        					),
        			),
        	),
        	'viewleave' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/leave/view/:id',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Leaves',
        							'action'     => 'viewleave',
        					),
        			),
        	),
        	'updatedoj' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/users/updatedoj',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'updatedoj',
        					),
        			),
        	),
        	
        	'milestoneforallproject' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addmilestoneforallproject',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Script',
        							'action'     => 'milestoneforallproject',
        					),
        			),
        	),
        	
        	'script' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/script',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Script',
        							'action'     => 'index',
        					),
        			),
        	),
        	
        	'loginslots' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/loginslots',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'loginslots',
        					),
        			),
        	),
        	'gridloginslots' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/gridloginslots',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'gridloginslots',
        					),
        			),
        	),
        	'addloginslots' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/addloginslots',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'addloginslots',
        					),
        			),
        	),
        	'deleteloginslots' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/deleteloginslots',
        					'defaults' => array(
        							'controller' => 'Application\Controller\User',
        							'action'     => 'deleteloginslots',
        					),
        			),
        	),
        	'projectreport' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        					'route'    => '/projectreport',
        					'defaults' => array(
        							'controller' => 'Application\Controller\Report',
        							'action'     => 'projectreport',
        					),
        			),
        	),
        	
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
    		//'Application\Controller\Activityhistory' => 'Application\Controller\ActivityhistoryController',
            'Application\Controller\Admin' => 'Application\Controller\AdminController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\Projects' => 'Application\Controller\ProjectsController',
            'Application\Controller\Projecttype' => 'Application\Controller\ProjecttypeController',
            'Application\Controller\Activities' => 'Application\Controller\ActivitiesController',          
            'Application\Controller\Activitylog' => 'Application\Controller\ActivitylogController',
    		'Application\Controller\Leaves' => 'Application\Controller\LeavesController',
    		'Application\Controller\Company' => 'Application\Controller\CompanyController',
    		'Application\Controller\Report' => 'Application\Controller\ReportController',
    		'Application\Controller\Script' => 'Application\Controller\ScriptController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'templates/newlyassignedactivities'=> __DIR__ . '/../view/newlyassignedactivities.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
            //'activity-history' => __DIR__ . '/../view',
        ),
        'helper_map' => array(
        		'loggedInAs' => 'Application\View\Helper\loggedInAs',
        ),
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
    ),
   
);
