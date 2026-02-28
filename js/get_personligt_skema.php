<?php

	function render_skema($id) {
		if (strlen(file_get_contents('https://personligtskema.ku.dk/ical.asp?objectclass=student&id=' . $id)) > 600) {
			$skema = file_get_contents('https://personligtskema.ku.dk/ical.asp?objectclass=student&id=' . $id);
			file_put_contents('kps/' . $id . '.ics', $skema);
			$ical = new ical('kps/' . $id . '.ics');
			$events = $ical->events();
			$output = "";
			$days = ['søn', 'man', 'tir', 'ons', 'tor', 'fre', 'lør'];

			$output .= '<h1 class="sau_title bar_top centered raised">Skema for ' . substr($skema, strpos($skema, 'Universitet Skema:') + 28, strpos($skema, 'BEGIN:VTIMEZONE') - (strpos($skema, 'Universitet Skema:') + 28)) . '</h1>';
			$output .= "<a href='javascript:logud()' id='logout_btn'>Yo mate, I'm out!</a>";
			$output .= "<a href='https://www.facebook.com/saushoppen/' target='_blank' id='help_btn'>Help!</a><br /><br />";
			$output .= '<div id="week_list" class="bar_bottom raised">Uger: ';

			$old_week = 0;
			$new_week = 0;
          
            $isSpring = (int)date('W') >= 5 && (int)date('W') <= 30;

			for ($i = 0; $i < count($events); $i++) {
				$e_d = $events[$i]['DESCRIPTION'];
				$e_s = $events[$i]['DTSTART'];
				$e_e = $events[$i]['DTEND'];
				$e_l = $events[$i]['LOCATION'];
				$e_a = $events[$i]['SUMMARY'];
				$event = "";

				$new_week = date('W', mktime(0,0,0,(int)substr($e_s,4,2),(int)substr($e_s,6,2),(int)substr($e_s,0,4)));

				if ($i == 0) {
					$o_ss = 0;
					for ($j = 0; $j < count($events); $j++) {
						$e_ss = $events[$j]['DTSTART'];
						$w_ss = (int)date('W', mktime(0,0,0,(int)substr($e_ss,4,2),(int)substr($e_ss,6,2),(int)substr($e_ss,0,4)));
                      
                        if ($isSpring && ($w_ss <5 || $w_ss >30)) { continue; }
                      
						if ($o_ss != $w_ss) {$output .= '<a class="sau_list" href="#sau_week_' . $w_ss . '">' . $w_ss . '</a>'; }
						$o_ss = $w_ss;
					}
					$output .= '<div id="me"><a href="https://www.facebook.com/davidsvane" target="_blank">David Svane</a></div></div>';
                  
                    if ($isSpring && ($new_week <5 || $new_week >30)) { continue; }
                  
                    $output .= '<h2 class="sau_week centered" id="sau_week_' . (int)$new_week . '">Uge ' . (int)$new_week . '</h2>';
                    $output .= '<table class="personligt_skema" cellspacing="0" cellpadding="0">';
					$output .= '<tr class="ps_titles"><td class="date">Dato</td><td class="start" colspan="1">Tid</td><td>Aktivitet</td><td class="loc">Lokale</td></tr>';
				} else if ($old_week != $new_week) {
                  
                    if ($isSpring && ($new_week <5 || $new_week >30)) { continue; }
                  
					$output .= '</table>';
					$output .= '<h2 class="sau_week centered" id="sau_week_' . (int)$new_week . '">Uge ' . (int)$new_week . '</h2>';
					$output .= '<table class="personligt_skema" cellspacing="0" cellpadding="0">';
					$output .= '<tr class="ps_titles"><td class="date">Dato</td><td class="start" colspan="1">Tid</td><td>Aktivitet</td><td class="loc">Lokale</td></tr>';
				}
				$old_week = $new_week;
              
                if ($isSpring && ($new_week <5 || $new_week >30)) { continue; }

				$event .= '<tr id="e_' . $i . '" class="not_title row_' . ($i%2==0?'odd':'even') . '">';
				$event .= '<td class="date">' . $days[(int)date('w', mktime(0,0,0,(int)substr($e_s,4,2),(int)substr($e_s,6,2),(int)substr($e_s,0,4)))]  . ' ' . substr($e_s,6,2) . '/' . substr($e_s,4,2) . '</td>';
				$event .= '<td class="start">' . substr($e_s,9,2) . ':' . substr($e_s,11,2) . ' - ' . substr($e_e,9,2) . ':' . substr($e_e,11,2) . '</td>';
				$event_code = substr($e_d, strpos($e_d, 'Activity:') + 10, strpos($e_d, '.\nBeskrivelse') - (strpos($e_d, 'Activity:') + 10));
				$event .= '<td class="code">' . $event_code . '</td>';
				$event .= '<td class="loc">' . $e_l . '</td>';
				$event .= '</tr>';

				$event .= '<tr id="e_' . $i . '_more" class="row_more row_' . ($i%2==0?'odd':'even') . '">';
				$event .= '<td class="summary" colspan="4">' . $e_a . ' - ';
				$event .= substr($e_d, strpos($e_d, 'Student Set:') + 12) . ' - ';
				$event .= substr($e_d, strpos($e_d, 'Description:') + 12, strpos($e_d, '.\nUndervisningstype') - (strpos($e_d, 'Description:') + 12)) . ' - ';
				$event .= substr($e_d, strpos($e_d, 'Staff:') + 7, strpos($e_d, '.\nLokale') - (strpos($e_d, 'Staff:') + 7)) . '</td>';
				$event .= '</tr>';

				$output .= $event;

				if ($i == count($events)-1) {
					$output .= '</table>';
				}
			}
			$output .= '<br /><br /><br />';

      unlink('kps/' . $id . '.ics');
			return $output;
		} else {
			return false;
		}
	}
?>
