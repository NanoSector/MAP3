<?php

/** 
 * MAP3 Software
 * Open source forum software written in PHP 
 *  
 * Version: 1.0 Alpha 
 * Developed by: Rick "Yoshi2889" and Robert "Stijllozekip" 
 * Website: http://map3cms.co.cc 
 *  
 * A list of all contributors can be found on the credits page in the admin panel 
 * 
 * License: BSD
 *
*/

function hsidebar()
{
	global $urls;
        echo '
                <div class="span3">
                        <div class="well sidebar-nav">
                                <ul class="nav nav-list">
                                        <li class="nav-header">Plans</li>';
                                        
        $array = array(
                'home' => 'Home',
                'core' => 'Core Features',
                'apis' => 'APIs',
        );
        
        $active = 'home';
        if (!empty($_GET['sa']) && array_key_exists($_GET['sa'], $array))
                $active = $_GET['sa'];
                
        foreach ($array as $id => $link)
                echo '
                                        <li', $active == $id ? ' class="active"' : '', '><a href="', $urls['script'], '?act=help', $id != 'home' ? '&sa=' . $id : '', '">', $link, '</a></li>';
                        
        echo '
                                </ul>
                        </div>
                </div>';
}
function template_home()
{
        echo '
        <div class="row-fluid">';
        
        hsidebar();
        
        echo '
                <div class="span9">
                        <div class="hero-unit">
                                <h1>The MAP3 Software</h1>
                                <p>The new MAP3 software is going to aim at being an innovative portion of software, and maybe even one of the most extensible software.</p>
                                <!--<p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>-->
                        </div>
                        <div class="span7">
                                <h2>Plans</h2>
                                <p>Even though the software isn\'t finished by far we already have a lot of plans for it.</p>
                                <p>Click one of the links in the sidebar to read more.</p>
                        </div>
                </div>
        </div>';
}

function template_core()
{
        echo '
        <div class="row-fluid">';
        
        hsidebar();
        
        echo '
                <div class="span9">
                        <div class="hero-unit">
                                <h1>The core powering MAP3</h1>
                                <p>The core powering MAP3 is written from the ground up. It provides powerful features and is blazing fast.</p>
                        </div>
                        <div class="span4">
                                <h2>Extensible</h2>
                                <p>It is very easy to write plugins for the core. Installing a plugin is as simple as just unzipping the files.</p>
                        </div>
                        <div class="span4">
                                <h2>Powerful features</h2>
                                <p>Whether it is executing a simple database query or performing large sheduled tasks, the core is always ready for these tasks. And so is your plugin.</p>
                        </div>
                        <div class="span4">
                                <h2>Speed without a limit</h2>
                                <p>The core has been designed to provide speed like no other, while not compromising functionality. Enjoy blazing fast loading speeds.</p>
                        </div>
                        <div class="span4">
                                <h2>Forum included</h2>
                                <p>Starting a community has never been easier. Just install MAP3 and you can enable the forum straight away.</p>
                        </div>
                </div>
        </div>';
}
