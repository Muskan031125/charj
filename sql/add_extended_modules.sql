-- ============================================================
-- Charj.in — Extended & New Modules
-- Combines: add_tables.sql + add_tables_v3.sql
-- Run this AFTER charj_schema.sql
-- Safe to re-run (uses CREATE TABLE IF NOT EXISTS + INSERT IGNORE)
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+05:30';

-- ============================================================
-- 1. SUBSIDIES — State-wise EV subsidy schemes
-- ============================================================
CREATE TABLE IF NOT EXISTS `subsidies` (
  `id`           int(11)      NOT NULL AUTO_INCREMENT,
  `state`        varchar(100) NOT NULL,
  `vehicle_type` enum('2W','3W','4W') NOT NULL,
  `scheme_name`  varchar(255) NOT NULL,
  `amount`       int(11)      NOT NULL DEFAULT 0,
  `conditions`   text,
  `valid_until`  date,
  `source_url`   varchar(500),
  `is_active`    tinyint(1)   NOT NULL DEFAULT 1,
  `created_at`   datetime     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   datetime     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `vehicle_type` (`vehicle_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 2. OWNER QUESTIONS — Community Q&A per vehicle
-- ============================================================
CREATE TABLE IF NOT EXISTS `owner_questions` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `vehicle_id`  int(11)      NOT NULL,
  `name`        varchar(100) NOT NULL,
  `question`    text         NOT NULL,
  `answer`      text,
  `votes`       int(11)      NOT NULL DEFAULT 0,
  `is_approved` tinyint(1)   NOT NULL DEFAULT 0,
  `created_at`  datetime     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  datetime     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- 3. EV GLOSSARY — Plain-English EV term definitions
-- ============================================================
CREATE TABLE IF NOT EXISTS `ev_glossary` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `term`       varchar(200) NOT NULL,
  `slug`       varchar(200) NOT NULL,
  `definition` text         NOT NULL,
  `category`   enum('battery','charging','performance','finance','general','policy') NOT NULL DEFAULT 'general',
  `created_at` datetime     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `ev_glossary` (`term`, `slug`, `definition`, `category`) VALUES
('BMS (Battery Management System)',   'bms-battery-management-system',   'System that monitors and manages a battery pack\'s state, including SOC, SOH, temperature, and cell balancing.',                       'battery'),
('SOC (State of Charge)',             'soc-state-of-charge',             'Percentage of current charge relative to maximum capacity. 100% SOC = fully charged.',                                               'battery'),
('SOH (State of Health)',             'soh-state-of-health',             'Measure of a battery\'s capacity compared to when it was new. 80% SOH is typically considered end-of-life.',                         'battery'),
('Range Anxiety',                     'range-anxiety',                   'The fear that an EV\'s battery will run out before reaching the destination or charging point.',                                       'general'),
('FAME II',                           'fame-ii',                         'Faster Adoption and Manufacturing of Electric Vehicles Phase II — India\'s central subsidy scheme for EVs.',                          'policy'),
('CHAdeMO',                           'chademo',                         'Japanese DC fast charging standard. Being phased out in India in favor of CCS2.',                                                     'charging'),
('CCS2 (Combined Charging System 2)', 'ccs2-combined-charging-system-2', 'DC fast charging standard used in India for 4-wheelers. Supports up to 350kW.',                                                      'charging'),
('Type 2',                            'type-2',                          'AC charging standard for EVs. Most home and public AC chargers in India use Type 2 (7.4kW-22kW).',                                   'charging'),
('AC Charging',                       'ac-charging',                     'Alternating current charging, typically slower (3.3kW-22kW). Used at home and most public stations.',                                 'charging'),
('DC Fast Charging',                  'dc-fast-charging',                'Direct current charging, much faster (25kW-150kW+). Converts AC to DC externally.',                                                   'charging'),
('Regenerative Braking',              'regenerative-braking',            'System that converts kinetic energy during braking back into electrical energy, increasing range.',                                    'performance'),
('Torque',                            'torque',                          'Rotational force. EVs deliver maximum torque instantly (0 RPM), enabling quick acceleration.',                                        'performance'),
('kWh (Kilowatt-hour)',               'kwh-kilowatt-hour',               'Unit of energy. A 5kWh battery can deliver 5,000 watts for 1 hour. Larger kWh = more range.',                                       'battery'),
('kW (Kilowatt)',                     'kw-kilowatt',                     'Unit of power. Determines max speed and acceleration. 1 kW ≈ 1.34 horsepower.',                                                      'performance'),
('IP Rating',                         'ip-rating',                       'Ingress Protection rating indicating dust and water resistance. IP67 = dustproof + waterproof to 1m.',                                'general'),
('NMC Battery',                       'nmc-battery',                     'Nickel Manganese Cobalt battery chemistry. High energy density, used in premium EVs. Ola S1, Ather.',                                'battery'),
('LFP Battery',                       'lfp-battery',                     'Lithium Iron Phosphate battery. Longer cycle life, safer, lower energy density. Used in Tata EVs.',                                  'battery'),
('OBC (On-Board Charger)',            'obc-on-board-charger',            'Converts AC from the charging point to DC to charge the battery. Limits max AC charging speed.',                                    'charging'),
('V2G (Vehicle to Grid)',             'v2g-vehicle-to-grid',             'Technology allowing EVs to discharge power back to the electricity grid. Not yet available in India.',                               'charging'),
('80EEB',                             '80eeb',                           'Indian Income Tax section allowing deduction of up to ₹1.5 lakh on EV loan interest for individuals.',                               'finance'),
('BESCOM/MSEDCL',                     'bescom-msedcl',                   'State electricity distribution companies (Karnataka/Maharashtra). Set EV tariff rates.',                                             'charging'),
('Traction Motor',                    'traction-motor',                  'The main motor that drives the wheels in an EV. Types: PMSM (permanent magnet), induction.',                                         'performance'),
('Cell-to-Pack (CTP)',                'cell-to-pack-ctp',                'Battery design where cells are directly integrated into the pack without modules. Better space efficiency.',                          'battery'),
('ARAI Range',                        'arai-range',                      'Range certified by Automotive Research Association of India. Real-world range is typically 70-85% of ARAI figure.',                  'general'),
('Charging Ecosystem',                'charging-ecosystem',              'Network of charging stations, home chargers, and associated services that support EV adoption.',                                      'charging');

-- ============================================================
-- 4. SPARE PARTS — EV spare parts catalog
-- ============================================================
CREATE TABLE IF NOT EXISTS `spare_parts` (
  `id`                int(11)      NOT NULL AUTO_INCREMENT,
  `part_name`         varchar(255) NOT NULL,
  `slug`              varchar(255) NOT NULL,
  `category`          enum('battery','motor','charger','tyre','brake','body','electrical','other') NOT NULL,
  `part_number`       varchar(100),
  `price`             decimal(10,2),
  `compatible_models` text,
  `description`       text         NOT NULL,
  `image_url`         varchar(500),
  `vendor_name`       varchar(255),
  `vendor_contact`    varchar(255),
  `vendor_url`        varchar(500),
  `in_stock`          tinyint(1)   NOT NULL DEFAULT 1,
  `status`            enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at`        datetime     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        datetime     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `spare_parts` (`part_name`, `slug`, `category`, `part_number`, `price`, `compatible_models`, `description`, `vendor_name`, `vendor_contact`, `vendor_url`, `in_stock`, `status`) VALUES
('Lithium-Ion Battery Pack 48V 100Ah', 'lithium-ion-battery-pack-48v',   'battery', 'BAT-LIO-48V-100AH', 45000.00, 'Bajaj Chetak, Hero Electric, Scooty',          'Premium quality lithium-ion battery with advanced BMS for extended range, safety, and durability. Compatible with most 2-wheeler and 3-wheeler EVs. Includes 2-year warranty.',                                                          'Lion EV',                  '0422-4297777',      'https://www.lionev.in/',         1, 'published'),
('BLDC Motor 5kW 48V',                 'bldc-motor-5kw-48v',             'motor',   'MOT-BLDC-5KW',      22000.00, 'Hero Electric, Bajaj Auto, TVS',               'Brushless DC motor with peak efficiency for smooth acceleration and silent operation. Ideal for 2-wheelers and 3-wheelers. Long service life with minimal maintenance.',                                                                    'Motor Kit',                'sales@motorkit.in', 'https://motorkit.in/',           1, 'published'),
('Wall-Mounted Fast Charger 7kW AC',   'wall-mounted-fast-charger-7kw',  'charger', 'CHR-AC-7KW-32A',    28000.00, 'All EV models with Type 2 AC port',            'Home and office wall-mounted fast charger with temperature monitoring, overload protection, and Wi-Fi connectivity. Charges most EVs in 6-8 hours. Indian Standards compliant.',                                                           'RetroEV',                  'info@retroev.in',   'https://retroev.in/spares.php',  1, 'published'),
('Premium Lithium Battery 60V 100Ah',  'premium-lithium-battery-60v',    'battery', 'BAT-LIO-60V-100AH', 65000.00, 'Tata Nexon EV, MG ZS EV, Mahindra XUV400',    'High-capacity lithium-ion battery for 4-wheeler EVs. Offers 400-500km range with advanced thermal management. ISO 9001 certified with 5-year warranty.',                                                                                  'Smart-Tech E-Rickshaw',    'sales@smarttech-ev.in', 'https://smarttech-ev.in/',    1, 'published'),
('Electric Scooter Motor Kit Complete', 'electric-scooter-motor-kit',    'motor',   'KIT-SCOOTER-1500W', 15000.00, 'Generic 2-wheeler, DIY conversions',           'Complete DIY electric scooter motor kit including 1500W brushless motor, controller, and wiring harness. Perfect for custom builds and conversions. Installation guide included.',                                                            'PATOYS',                   'care@patoys.in',    'https://www.patoys.in/',         1, 'published');

-- ============================================================
-- 5. EVENTS — EV expos, launches, meetups
-- ============================================================
CREATE TABLE IF NOT EXISTS `events` (
  `id`               int(11)      NOT NULL AUTO_INCREMENT,
  `title`            varchar(255) NOT NULL,
  `slug`             varchar(255) NOT NULL,
  `description`      text         NOT NULL,
  `event_type`       enum('expo','launch','test-drive','webinar','meetup','other') NOT NULL,
  `start_date`       datetime     NOT NULL,
  `end_date`         datetime,
  `city`             varchar(100),
  `venue_address`    text,
  `organizer`        varchar(255),
  `registration_url` varchar(500),
  `banner_image`     varchar(500),
  `is_featured`      tinyint(1)   NOT NULL DEFAULT 0,
  `status`           enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at`       datetime     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       datetime     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `start_date` (`start_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `events` (`title`, `slug`, `event_type`, `start_date`, `end_date`, `city`, `venue_address`, `organizer`, `registration_url`, `description`, `status`, `is_featured`) VALUES
('EVTECH India Expo 2026 Delhi',       'evtech-india-expo-2026-delhi',   'expo', '2026-04-17 10:00:00', '2026-04-19 18:00:00', 'New Delhi',     'Bharat Mandapam, Pragati Maidan, New Delhi',                  'EVTECH',            'https://evtechindiaexpo.com/registration', 'India''s premier electric vehicle technology expo. Featuring latest EV launches, battery technology innovations, charging infrastructure, and mobility solutions. 250+ exhibitors, free entry with registration.',                           'published', 1),
('EV India Expo 2026 Greater Noida',   'ev-india-expo-2026-noida',       'expo', '2026-09-01 10:00:00', '2026-09-03 18:00:00', 'Greater Noida', 'India Expo Centre, Greater Noida, NCR',                       'EV India',          'https://evindiaexpo.in/register',          'The 6th edition of India''s largest EV expo. Showcase of electric vehicles, charging networks, and sustainable mobility solutions. Free entry with online registration. 300+ exhibitors expected.',                                            'published', 1),
('India International EV Show 2026',   'iiev-show-2026-pune',            'expo', '2026-10-02 10:00:00', '2026-10-04 18:00:00', 'Pune',          'Auto Cluster Exhibition Centre, Pimpri-Chinchwad, Pune',      'IIEV',              'https://iievshow.com/register',            'The 8th edition of India International EV Show featuring cutting-edge EV technology, battery innovations, and industry leaders. Witness live product launches and test drive latest models.',                                                  'published', 1),
('Auto EV Bharat 2026 Bengaluru',      'auto-ev-bharat-2026',            'expo', '2026-08-15 10:00:00', '2026-08-17 18:00:00', 'Bengaluru',     'KTPO Convention Centre, Whitefield, Bengaluru',               'Auto EV Bharat',    'https://www.autoevbharat.com/register',    'Bangalore''s premier automotive and EV technology expo. 250+ exhibitors from 15+ countries. Expected 25,000+ visitors. Explore latest EV technologies, batteries, and mobility solutions.',                                                    'published', 0),
('EV Expo December 2026 Delhi',        'ev-expo-december-2026',          'expo', '2026-12-23 10:00:00', '2026-12-25 18:00:00', 'New Delhi',     'Bharat Mandapam, Hall 3-5, New Delhi',                        'EV Expo Organizers','https://evexpo.in/register',               'Year-end electric vehicle showcase and marketplace. Latest model launches, exclusive deals, and networking opportunities. Perfect time to explore and purchase your next EV.',                                                                 'published', 0);

-- ============================================================
-- 6. ANNOUNCEMENTS — News, policy updates, product launches
-- ============================================================
CREATE TABLE IF NOT EXISTS `announcements` (
  `id`           int(11)      NOT NULL AUTO_INCREMENT,
  `title`        varchar(255) NOT NULL,
  `slug`         varchar(255) NOT NULL,
  `content`      text         NOT NULL,
  `type`         enum('general','policy','product','partnership','press') NOT NULL DEFAULT 'general',
  `is_pinned`    tinyint(1)   NOT NULL DEFAULT 0,
  `banner_image` varchar(500),
  `link_url`     varchar(500),
  `status`       enum('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` datetime,
  `created_at`   datetime     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   datetime     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `is_pinned` (`is_pinned`),
  KEY `published_at` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `announcements` (`title`, `slug`, `type`, `content`, `is_pinned`, `status`, `published_at`) VALUES
('Tata Sierra EV Launched on June 30, 2026',          'tata-sierra-ev-launched',     'product', 'Tata Motors has launched the much-awaited Tata Sierra EV on June 30, 2026. The new electric SUV features a claimed driving range of up to 600 km, advanced Vehicle-to-Load (V2L) charging capability, independent rear suspension, and premium interiors. Available in multiple variants with dual battery options. Bookings now open on Tata website.',                                                                                                                                          1, 'published', '2026-06-30 14:00:00'),
('BYD Plug-in Hybrid Unveiled for Indian Market',      'byd-phev-india-launch',       'product', 'BYD has officially unveiled its first plug-in hybrid electric vehicle (PHEV) for the Indian market on June 9, 2026. The Sealion 6 PHEV combines a 1.5-litre petrol engine with an electric motor, offering a combined driving range of nearly 1,000 km. Features include intelligent AWD, advanced infotainment, and eco-friendly performance. Launch expected in Q3 2026.',                                                                                                                    1, 'published', '2026-06-09 10:30:00'),
('Toyota Urban Cruiser Ebella: Toyota Enters Pure EV Space', 'toyota-urban-cruiser-ebella', 'product', 'Toyota has officially entered India''s pure Battery Electric Vehicle (BEV) market with the Urban Cruiser Ebella launched in June 2026. The compact EV features dual battery configurations — standard 49 kWh pack and premium 61 kWh option with up to 543 km range. Focuses on urban efficiency and practicality. Pre-orders now open.',                                                                                                                                                0, 'published', '2026-06-15 09:00:00'),
('Hyundai Inster EV: Affordable Compact Electric City Car', 'hyundai-inster-ev-india',  'product', 'Hyundai has announced the Inster EV, an affordable and tech-heavy compact electric vehicle designed for Indian urban markets. Priced competitively, the Inster EV emphasizes maneuverability, efficient city driving, and modern technology features. Expected launch in H2 2026. Perfect for daily commuting with impressive range and charging efficiency.',                                                                                                                               0, 'published', '2026-06-20 11:30:00'),
('FAME II Subsidy Scheme Extended Until December 2026', 'fame-ii-subsidy-extended',   'policy',  'The Government of India has announced an extension of the FAME II subsidy scheme until December 31, 2026. The subsidy provides substantial discounts on purchase of electric two-wheelers, three-wheelers, and four-wheelers. Check your state''s specific subsidy limits and eligibility criteria on the Ministry of Heavy Industries website.',                                                                                                                                           1, 'published', '2026-06-18 16:00:00'),
('Tata Motors Price Increase Effective July 1, 2026',  'tata-motors-price-hike',      'policy',  'Tata Motors has announced a price increase of up to 1.5% across its passenger vehicle portfolio, both ICE and EV models, effective July 1, 2026. The hike is attributed to rising input costs and operational expenses. Existing pending bookings and orders placed before June 30 will not be affected. Check official Tata Motors website for detailed pricing updates.',                                                                                                               0, 'published', '2026-06-25 12:00:00');
