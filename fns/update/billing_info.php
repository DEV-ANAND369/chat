<?php

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';

if (role(['permissions' => ['memberships' => 'view_membership_info']])) {

    $noerror = true;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';

    $countries = [
        "afghanistan" => "Afghanistan", "albania" => "Albania", "algeria" => "Algeria", "andorra" => "Andorra", "angola" => "Angola",
        "antigua_and_barbuda" => "Antigua and Barbuda", "argentina" => "Argentina", "armenia" => "Armenia", "australia" => "Australia",
        "austria" => "Austria", "azerbaijan" => "Azerbaijan", "bahamas" => "Bahamas", "bahrain" => "Bahrain", "bangladesh" => "Bangladesh",
        "barbados" => "Barbados", "belarus" => "Belarus", "belgium" => "Belgium", "belize" => "Belize", "benin" => "Benin",
        "bhutan" => "Bhutan", "bolivia" => "Bolivia", "bosnia_and_herzegovina" => "Bosnia and Herzegovina", "botswana" =>
        "Botswana", "brazil" => "Brazil", "brunei" => "Brunei", "bulgaria" => "Bulgaria", "burkina_faso" => "Burkina Faso",
        "burundi" => "Burundi", "cabo_verde" => "Cabo Verde", "cambodia" => "Cambodia", "cameroon" => "Cameroon", "canada" => "Canada",
        "central_african_republic" => "Central African Republic", "chad" => "Chad", "chile" => "Chile", "china" => "China",
        "colombia" => "Colombia", "comoros" => "Comoros", "congo_brazzaville" => "Congo (Brazzaville)", "congo_kinshasa" => "Congo (Kinshasa)",
        "costa_rica" => "Costa Rica", "cote_divoire" => "Cote d'Ivoire", "croatia" => "Croatia", "cuba" => "Cuba", "cyprus" => "Cyprus",
        "czechia" => "Czechia", "denmark" => "Denmark", "djibouti" => "Djibouti", "dominica" => "Dominica",
        "dominican_republic" => "Dominican Republic", "ecuador" => "Ecuador", "egypt" => "Egypt", "el_salvador" => "El Salvador",
        "equatorial_guinea" => "Equatorial Guinea", "eritrea" => "Eritrea", "estonia" => "Estonia", "eswatini" => "Eswatini",
        "ethiopia" => "Ethiopia", "fiji" => "Fiji", "finland" => "Finland", "france" => "France", "gabon" => "Gabon", "gambia" => "Gambia",
        "georgia" => "Georgia", "germany" => "Germany", "ghana" => "Ghana", "greece" => "Greece", "grenada" => "Grenada",
        "guatemala" => "Guatemala", "guinea" => "Guinea", "guinea_bissau" => "Guinea-Bissau", "guyana" => "Guyana", "haiti" => "Haiti",
        "honduras" => "Honduras", "hungary" => "Hungary", "iceland" => "Iceland", "india" => "India", "indonesia" => "Indonesia",
        "iran" => "Iran", "iraq" => "Iraq", "ireland" => "Ireland", "israel" => "Israel", "italy" => "Italy", "jamaica" => "Jamaica",
        "japan" => "Japan", "jordan" => "Jordan", "kazakhstan" => "Kazakhstan", "kenya" => "Kenya", "kiribati" => "Kiribati",
        "korea_north" => "Korea, North", "korea_south" => "Korea, South", "kosovo" => "Kosovo", "kuwait" => "Kuwait",
        "kyrgyzstan" => "Kyrgyzstan", "laos" => "Laos", "latvia" => "Latvia", "lebanon" => "Lebanon", "lesotho" => "Lesotho",
        "liberia" => "Liberia", "libya" => "Libya", "liechtenstein" => "Liechtenstein", "lithuania" => "Lithuania",
        "luxembourg" => "Luxembourg", "madagascar" => "Madagascar", "malawi" => "Malawi", "malaysia" => "Malaysia", "maldives" => "Maldives",
        "mali" => "Mali", "malta" => "Malta", "marshall_islands" => "Marshall Islands", "mauritania" => "Mauritania",
        "mauritius" => "Mauritius", "mexico" => "Mexico", "micronesia" => "Micronesia", "moldova" => "Moldova", "monaco" => "Monaco",
        "mongolia" => "Mongolia", "montenegro" => "Montenegro", "morocco" => "Morocco", "mozambique" => "Mozambique",
        "myanmar_burma" => "Myanmar (Burma)", "namibia" => "Namibia", "nauru" => "Nauru", "nepal" => "Nepal", "netherlands" => "Netherlands",
        "new_zealand" => "New Zealand", "nicaragua" => "Nicaragua", "niger" => "Niger", "nigeria" => "Nigeria",
        "north_macedonia" => "North Macedonia", "norway" => "Norway", "oman" => "Oman", "pakistan" => "Pakistan", "palau" => "Palau",
        "palestine" => "Palestine", "panama" => "Panama", "papua_new_guinea" => "Papua New Guinea", "paraguay" => "Paraguay", "peru" => "Peru",
        "philippines" => "Philippines", "poland" => "Poland", "portugal" => "Portugal", "qatar" => "Qatar", "romania" => "Romania",
        "russia" => "Russia", "rwanda" => "Rwanda", "saint_kitts_and_nevis" => "Saint Kitts and Nevis", "saint_lucia" => "Saint Lucia",
        "saint_vincent_and_the_grenadines" => "Saint Vincent and the Grenadines", "samoa" => "Samoa", "san_marino" => "San Marino",
        "sao_tome_and_principe" => "Sao Tome and Principe", "saudi_arabia" => "Saudi Arabia", "senegal" => "Senegal", "serbia" => "Serbia",
        "seychelles" => "Seychelles", "sierra_leone" => "Sierra Leone", "singapore" => "Singapore", "slovakia" => "Slovakia",
        "slovenia" => "Slovenia", "solomon_islands" => "Solomon Islands", "somalia" => "Somalia", "south_africa" => "South Africa",
        "south_sudan" => "South Sudan", "spain" => "Spain", "sri_lanka" => "Sri Lanka", "sudan" => "Sudan", "suriname" => "Suriname",
        "sweden" => "Sweden", "switzerland" => "Switzerland", "syria" => "Syria", "taiwan" => "Taiwan", "tajikistan" => "Tajikistan",
        "tanzania" => "Tanzania", "thailand" => "Thailand", "timor_leste" => "Timor-Leste", "togo" => "Togo", "tonga" => "Tonga",
        "trinidad_and_tobago" => "Trinidad and Tobago", "tunisia" => "Tunisia", "turkey" => "Turkey", "turkmenistan" => "Turkmenistan",
        "tuvalu" => "Tuvalu", "uganda" => "Uganda", "ukraine" => "Ukraine", "united_arab_emirates" => "United Arab Emirates",
        "united_kingdom" => "United Kingdom", "united_states_of_america" => "United States of America", "uruguay" => "Uruguay",
        "uzbekistan" => "Uzbekistan", "vanuatu" => "Vanuatu", "vatican_city" => "Vatican City", "venezuela" => "Venezuela",
        "vietnam" => "Vietnam", "yemen" => "Yemen", "zambia" => "Zambia", "zimbabwe" => "Zimbabwe"
    ];

    $countries = array_keys($countries);
    $billing_data = array();

    $required_fields = [
        'postal_code', 'country', 'state', 'city', 'billed_to', 'street_address'
    ];

    foreach ($required_fields as $required_field) {
        if (!isset($data[$required_field]) || empty($data[$required_field])) {
            $result['error_variables'][] = [$required_field];
            $noerror = false;
        } else {
            if ($required_field !== 'country') {
                $billing_data[$required_field] = htmlspecialchars($data[$required_field], ENT_QUOTES, 'UTF-8');
            } else {
                $billing_data[$required_field] = $data[$required_field];
            }
        }
    }

    if ($noerror) {
        if (!in_array($data['country'], $countries)) {
            $noerror = false;
            $result['error_variables'][] = ['country'];
        }
    }

    if ($noerror) {
        $columns = $join = $where = null;
        $columns = ['billed_to', 'street_address', 'city', 'state', 'country', 'postal_code'];
        $where["billing_address.user_id"] = Registry::load('current_user')->id;

        $billing_address = DB::connect()->select('billing_address', $columns, $where);

        if (empty($billing_address)) {
            $billing_data['user_id'] = Registry::load('current_user')->id;
            DB::connect()->insert('billing_address', $billing_data);
        } else {
            DB::connect()->update('billing_address', $billing_data, ['user_id' => Registry::load('current_user')->id]);
        }

        $result = array();
        $result['success'] = true;
        $result['todo'] = 'reload';
        $result['reload'] = ['transactions'];
    }
}
?>