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

function template_main()
{
        global $context, $boards, $urls;
        
	// The board name.
                echo '
                        <h2>', $boards->boards[$context['bid']]['name'], '</h2>';
                
        if (isset($context['topics']))
        {

                echo '
                	<div class="cat_bar">
	       			Topics in this board
	       		</div>
                        <table class="list" cellspacing="0">
                                <thead class="cat_bar bg_color">
                                        <th style="width:5%"></th>
                                        <th style="width:51%">Name</th>
                                        <th style="width:10%"># Replies</th>
                                        <th style="width:7%"># Views</th>
                                        <th style="width:27%">Last Reply</th>
                                </thead>
                                <tbody>';
                
                $wbg = 1;
                foreach ($context['topics'] as $topic)
                {
                        $last = end($topic['posts']);
                        echo '
                                        <tr class="wbg', $wbg, '">
                                                <td class="bicon"><img src="', $urls['images'], '/normal_topic.png" style="height:16px;width:16px" /></td>
                                                <td><a href="', $urls['script'], '?act=topic&topicid=', $topic['id_topic'], '">', $topic['subject'], '</a> by <a href="', $urls['script'], '?act=profile&user=', $topic['member'], '">', $topic['member_name'], '</a></td>
                                                <td class="centertext">', count($topic['posts']), '<br />
                                                Posts</td>
                                                <td class="centertext">', $topic['views'], '<br />
                                                Views</td>';
                                                
                        if (!empty($topic['posts']))
                                echo '
                                                <td style="width:20%"><a href="', $urls['script'], '?act=topic&topicid=', $topic['id_topic'], '&msg', $last['id_msg'], '">', $last['subject'] == '' ? 'Re: ' . $topic['subject'] : $last['subject'], '</a> by <a href="', $urls['script'], '?act=profile&user=', $last['member'], '">', $last['member_name'], '</a><br />
                                                on ', date('D jS F Y H:i:s', $last['time_posted']), '</td>';
                        else
                                echo '
                                                <td style="width:20%">N/A<br />
                                                on N/A</td>';
                                                
                        echo '
                                        </tr>';
                                        
                        $wbg = $wbg == 1 ? 2 : 1;
                                                
                }
        
                echo '
                                </tbody>
                        </table>';
                
        }
        else
        {
                echo '
        <h2>An error has occured</h3>
        <p>An error occured while loading the message index of the selected board. Please go back and try again.</p>';
        }
        
        echo '
                </div>
        </div>';
}
