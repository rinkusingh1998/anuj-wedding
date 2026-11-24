<?php

/**
 * Uttar Pradesh ka location data (states + cities + villages).
 *
 * Isko aap aaram se badha sakte hain:
 *   - 'districts' me naya district ya shehar add karein (pehla naam district ka main shehar hota hai).
 *   - 'villages' me gaon add karein.
 *   - 'featured' me wo slugs likhein jo website par sabse upar dikhne chahiye.
 *
 * Phir admin panel -> "SEO & Sitemap" -> "Import UP cities & services" daba dein.
 * Har shehar ka page uske saare service pages aur sitemap entry khud ban jayegi.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Main state
    |--------------------------------------------------------------------------
    */

    'state' => [
        'name' => 'Uttar Pradesh',
        'slug' => 'uttar-pradesh',
        'region' => 'North India',
        'is_visible' => true,
        'is_default' => true,
        'priority' => 100,
        'sort_order' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Ek udaharan: hidden state (ye website par nahi dikhega)
    |--------------------------------------------------------------------------
    |
    | Admin panel me is state ka toggle ON karne par iske saare pages
    | aur sitemap URLs apne aap live ho jayenge.
    |
    */

    'extra_states' => [
        [
            'name' => 'Delhi',
            'slug' => 'delhi',
            'region' => 'Delhi NCR',
            'is_visible' => false,
            'is_default' => false,
            'priority' => 70,
            'sort_order' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Districts aur unke shehar (pehla naam = district ka main shehar)
    |--------------------------------------------------------------------------
    */

    'districts' => [

        // --- Local belt (hamara main kaam yahin hota hai) ---
        'Bulandshahr' => ['Bulandshahr', 'Khurja', 'Sikandrabad', 'Jahangirabad', 'Anupshahr', 'Gulaothi', 'Shikarpur', 'Debai', 'Syana', 'Pahasu', 'Agauta', 'Chhatari', 'Lakhaoti', 'Bugrasi', 'Narora', 'Unchagaon', 'Khanpur', 'Kakore'],
        'Gautam Buddh Nagar' => ['Noida', 'Greater Noida', 'Dadri', 'Jewar', 'Rabupura', 'Dankaur'],
        'Aligarh' => ['Aligarh', 'Khair', 'Atrauli', 'Iglas', 'Gabhana', 'Akrabad', 'Chandaus', 'Jalali', 'Gangiri', 'Pilkhana'],

        // --- Baaki Uttar Pradesh ---
        'Agra' => ['Agra', 'Achhnera', 'Fatehabad', 'Kiraoli', 'Etmadpur', 'Bah', 'Fatehpur Sikri', 'Shamshabad'],
        'Ambedkar Nagar' => ['Akbarpur', 'Tanda', 'Jalalpur', 'Katehari', 'Bhiti', 'Alapur'],
        'Amethi' => ['Gauriganj', 'Amethi', 'Musafirkhana', 'Jais', 'Tiloi'],
        'Amroha' => ['Amroha', 'Hasanpur', 'Gajraula', 'Dhanaura', 'Naugawan Sadat'],
        'Auraiya' => ['Auraiya', 'Dibiyapur', 'Bidhuna', 'Achhalda', 'Ajitmal'],
        'Ayodhya' => ['Ayodhya', 'Faizabad', 'Rudauli', 'Bikapur', 'Milkipur', 'Sohawal'],
        'Azamgarh' => ['Azamgarh', 'Mehnagar', 'Lalganj', 'Phulpur', 'Sagri', 'Nizamabad'],
        'Baghpat' => ['Baghpat', 'Baraut', 'Khekra', 'Chhaprauli', 'Binauli', 'Doghat'],
        'Bahraich' => ['Bahraich', 'Nanpara', 'Kaiserganj', 'Mahasi', 'Payagpur'],
        'Ballia' => ['Ballia', 'Rasra', 'Sikandarpur', 'Bairia', 'Bansdih', 'Sahatwar', 'Chilkahar'],
        'Balrampur' => ['Balrampur', 'Utraula', 'Tulsipur', 'Pachperwa'],
        'Banda' => ['Banda', 'Atarra', 'Naraini', 'Baberu', 'Pailani', 'Tindwari'],
        'Barabanki' => ['Barabanki', 'Nawabganj', 'Haidergarh', 'Ramsanehi Ghat', 'Sirauli Gauspur', 'Ramnagar'],
        'Bareilly' => ['Bareilly', 'Aonla', 'Baheri', 'Faridpur', 'Meerganj', 'Bhojipura'],
        'Basti' => ['Basti', 'Harraiya', 'Rudhauli', 'Kalwari', 'Gaur'],
        'Bhadohi' => ['Gyanpur', 'Bhadohi', 'Aurai', 'Suriyawan', 'Gopiganj'],
        'Bijnor' => ['Bijnor', 'Chandpur', 'Nagina', 'Najibabad', 'Dhampur', 'Sherkot', 'Seohara', 'Kiratpur', 'Afzalgarh'],
        'Budaun' => ['Budaun', 'Bilsi', 'Sahaswan', 'Dataganj', 'Ujhani', 'Kakrala', 'Wazirganj'],
        'Chandauli' => ['Chandauli', 'Mughalsarai', 'Sakaldiha', 'Chakia'],
        'Chitrakoot' => ['Karwi', 'Manikpur', 'Rajapur'],
        'Deoria' => ['Deoria', 'Salempur', 'Barhaj', 'Rudrapur', 'Bhatni', 'Gauri Bazar', 'Baitalpur'],
        'Etah' => ['Etah', 'Aliganj', 'Jalesar', 'Marhara', 'Awagarh', 'Nidhauli Kalan'],
        'Etawah' => ['Etawah', 'Bharthana', 'Jaswantnagar', 'Saifai', 'Bakewar', 'Lakhna'],
        'Farrukhabad' => ['Fatehgarh', 'Farrukhabad', 'Kaimganj', 'Amritpur', 'Kamalganj', 'Kayamganj'],
        'Fatehpur' => ['Fatehpur', 'Bindki', 'Khaga', 'Haswa', 'Bahua'],
        'Firozabad' => ['Firozabad', 'Tundla', 'Shikohabad', 'Sirsaganj', 'Jasrana', 'Eka'],
        'Ghaziabad' => ['Ghaziabad', 'Loni', 'Muradnagar', 'Modinagar', 'Sahibabad', 'Faridnagar'],
        'Ghazipur' => ['Ghazipur', 'Mohammadabad', 'Zamania', 'Saidpur', 'Jakhanian', 'Kasimabad'],
        'Gonda' => ['Gonda', 'Mankapur', 'Colonelganj', 'Tarabganj', 'Wazirganj'],
        'Gorakhpur' => ['Gorakhpur', 'Bansgaon', 'Campierganj', 'Sahjanwa', 'Chauri Chaura', 'Pipraich', 'Gola'],
        'Hamirpur' => ['Hamirpur', 'Rath', 'Maudaha', 'Sumerpur', 'Muskara'],
        'Hapur' => ['Hapur', 'Garhmukteshwar', 'Babugarh', 'Dhaulana', 'Pilkhuwa'],
        'Hardoi' => ['Hardoi', 'Shahabad', 'Sandila', 'Bilgram', 'Sawayajpur', 'Pihani'],
        'Hathras' => ['Hathras', 'Sikandra Rao', 'Sadabad', 'Sasni', 'Mursan', 'Sahpau'],
        'Jalaun' => ['Orai', 'Jalaun', 'Konch', 'Kalpi', 'Ait', 'Madhogarh'],
        'Jaunpur' => ['Jaunpur', 'Shahganj', 'Machhlishahr', 'Mariyahun', 'Badlapur', 'Kerakat'],
        'Jhansi' => ['Jhansi', 'Garautha', 'Mauranipur', 'Moth', 'Barua Sagar', 'Chirgaon'],
        'Kannauj' => ['Kannauj', 'Chhibramau', 'Tirwa', 'Talgram'],
        'Kanpur Dehat' => ['Akbarpur', 'Sikandra', 'Rasulabad', 'Bhognipur', 'Derapur'],
        'Kanpur Nagar' => ['Kanpur', 'Bithoor', 'Ghatampur', 'Bilhaur'],
        'Kasganj' => ['Kasganj', 'Patiyali', 'Sahawar', 'Ganj Dundwara', 'Amanpur', 'Sidhpura'],
        'Kaushambi' => ['Manjhanpur', 'Sirathu', 'Bharwari', 'Chail'],
        'Kushinagar' => ['Padrauna', 'Kasya', 'Hata', 'Kaptanganj', 'Tamkuhi Raj'],
        'Lakhimpur Kheri' => ['Lakhimpur', 'Gola Gokarannath', 'Mohammadi', 'Palia Kalan', 'Dhaurahra', 'Nighasan'],
        'Lalitpur' => ['Lalitpur', 'Mahroni', 'Talbehat', 'Bar'],
        'Lucknow' => ['Lucknow', 'Malihabad', 'Mohanlalganj', 'Bakshi Ka Talab', 'Itaunja', 'Kakori', 'Nagram'],
        'Maharajganj' => ['Maharajganj', 'Nautanwa', 'Anandnagar', 'Farenda', 'Partawal', 'Siswa Bazar'],
        'Mahoba' => ['Mahoba', 'Charkhari', 'Kulpahar'],
        'Mainpuri' => ['Mainpuri', 'Bhongaon', 'Karhal', 'Ghiror', 'Kishni', 'Kurawali', 'Bewar'],
        'Mathura' => ['Mathura', 'Vrindavan', 'Chhata', 'Mant', 'Goverdhan', 'Kosi Kalan', 'Baldeo', 'Farah'],
        'Mau' => ['Mau', 'Ghosi', 'Madhuban', 'Muhammadabad Gohna', 'Kopaganj'],
        'Meerut' => ['Meerut', 'Mawana', 'Sardhana', 'Kithore', 'Hastinapur', 'Daurala'],
        'Mirzapur' => ['Mirzapur', 'Vindhyachal', 'Chunar', 'Ahraura', 'Kachhwa'],
        'Moradabad' => ['Moradabad', 'Bilari', 'Thakurdwara', 'Kanth'],
        'Muzaffarnagar' => ['Muzaffarnagar', 'Khatauli', 'Budhana', 'Jansath', 'Purquazi'],
        'Pilibhit' => ['Pilibhit', 'Bisalpur', 'Puranpur', 'Barkhera', 'Amaria'],
        'Pratapgarh' => ['Pratapgarh', 'Bela', 'Kunda', 'Patti', 'Raniganj'],
        'Prayagraj' => ['Prayagraj', 'Soraon', 'Phulpur', 'Handia', 'Meja', 'Karchana', 'Koraon'],
        'Rae Bareli' => ['Rae Bareli', 'Dalmau', 'Salon', 'Bachhrawan', 'Unchahar'],
        'Rampur' => ['Rampur', 'Milak', 'Suar', 'Bilaspur', 'Kemri'],
        'Saharanpur' => ['Saharanpur', 'Deoband', 'Gangoh', 'Nakur', 'Behat', 'Rampur Maniharan', 'Sarsawa'],
        'Sambhal' => ['Sambhal', 'Chandausi', 'Bahjoi', 'Gunnaur', 'Asmoli'],
        'Sant Kabir Nagar' => ['Khalilabad', 'Mehdawal', 'Dhanghata', 'Semariyawan'],
        'Shahjahanpur' => ['Shahjahanpur', 'Tilhar', 'Powayan', 'Jalalabad', 'Nigohi'],
        'Shamli' => ['Shamli', 'Kairana', 'Thanabhawan', 'Unn', 'Kandhla'],
        'Shravasti' => ['Bhinga', 'Ikauna'],
        'Siddharthnagar' => ['Naugarh', 'Bansi', 'Domariyaganj', 'Itwa', 'Shohratgarh'],
        'Sitapur' => ['Sitapur', 'Biswan', 'Mahmudabad', 'Misrikh', 'Laharpur', 'Sidhauli'],
        'Sonbhadra' => ['Robertsganj', 'Obra', 'Anpara', 'Chopan', 'Renukoot'],
        'Sultanpur' => ['Sultanpur', 'Kadipur', 'Lambhua', 'Dhanpatganj'],
        'Unnao' => ['Unnao', 'Bangarmau', 'Safipur', 'Hasanganj', 'Purwa', 'Bighapur', 'Maurawan'],
        'Varanasi' => ['Varanasi', 'Ramnagar', 'Sarnath', 'Pindra'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Gaon (starter set — admin panel se aur add kar sakte hain)
    |--------------------------------------------------------------------------
    */

    'villages' => [
        'Bulandshahr' => ['Roopwas', 'Pachgain'],
        'Gautam Buddh Nagar' => ['Rohi', 'Dayanatpur', 'Kishorpur', 'Parohi', 'Ranhera', 'Kureb'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Featured shehar (website par sabse upar + home page par)
    |--------------------------------------------------------------------------
    */

    'featured' => [
        'khurja',
        'sikandrabad',
        'bulandshahr',
        'gulaothi',
        'jahangirabad',
        'anupshahr',
        'shikarpur',
        'jewar',
        'greater-noida',
        'noida',
        'dadri',
        'aligarh',
        'khair',
        'hapur',
        'ghaziabad',
        'meerut',
        'mathura',
        'agra',
    ],

];
