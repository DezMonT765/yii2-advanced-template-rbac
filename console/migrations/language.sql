/*
Navicat MySQL Data Transfer

Source Server         : home
Source Server Version : 50545
Source Host           : localhost:3306
Source Database       : cinema

Target Server Type    : MYSQL
Target Server Version : 50545
File Encoding         : 65001

Date: 2016-01-17 19:42:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for language
-- ----------------------------
DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `language_id` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name_ascii` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of language
-- ----------------------------
INSERT INTO `language` VALUES ('af-ZA', 'af', 'za', 'Afrikaans', 'Afrikaans', '0');
INSERT INTO `language` VALUES ('ar-AR', 'ar', 'ar', '‏العربية‏', 'Arabic', '0');
INSERT INTO `language` VALUES ('az-AZ', 'az', 'az', 'Azərbaycan dili', 'Azerbaijani', '0');
INSERT INTO `language` VALUES ('be-BY', 'be', 'by', 'Беларуская', 'Belarusian', '0');
INSERT INTO `language` VALUES ('bg-BG', 'bg', 'bg', 'Български', 'Bulgarian', '0');
INSERT INTO `language` VALUES ('bn-IN', 'bn', 'in', 'বাংলা', 'Bengali', '0');
INSERT INTO `language` VALUES ('bs-BA', 'bs', 'ba', 'Bosanski', 'Bosnian', '0');
INSERT INTO `language` VALUES ('ca-ES', 'ca', 'es', 'Català', 'Catalan', '0');
INSERT INTO `language` VALUES ('cs-CZ', 'cs', 'cz', 'Čeština', 'Czech', '0');
INSERT INTO `language` VALUES ('cy-GB', 'cy', 'gb', 'Cymraeg', 'Welsh', '0');
INSERT INTO `language` VALUES ('da-DK', 'da', 'dk', 'Dansk', 'Danish', '0');
INSERT INTO `language` VALUES ('de-DE', 'de', 'de', 'Deutsch', 'German', '1');
INSERT INTO `language` VALUES ('el-GR', 'el', 'gr', 'Ελληνικά', 'Greek', '0');
INSERT INTO `language` VALUES ('en-GB', 'en', 'gb', 'English (UK)', 'English (UK)', '0');
INSERT INTO `language` VALUES ('en-PI', 'en', 'pi', 'English (Pirate)', 'English (Pirate)', '0');
INSERT INTO `language` VALUES ('en-UD', 'en', 'ud', 'English (Upside Down)', 'English (Upside Down)', '0');
INSERT INTO `language` VALUES ('en-US', 'en', 'us', 'English (US)', 'English (US)', '1');
INSERT INTO `language` VALUES ('eo-EO', 'eo', 'eo', 'Esperanto', 'Esperanto', '0');
INSERT INTO `language` VALUES ('es-ES', 'es', 'es', 'Español (España)', 'Spanish (Spain)', '0');
INSERT INTO `language` VALUES ('es-LA', 'es', 'la', 'Español', 'Spanish', '0');
INSERT INTO `language` VALUES ('et-EE', 'et', 'ee', 'Eesti', 'Estonian', '0');
INSERT INTO `language` VALUES ('eu-ES', 'eu', 'es', 'Euskara', 'Basque', '0');
INSERT INTO `language` VALUES ('fa-IR', 'fa', 'ir', '‏فارسی‏', 'Persian', '0');
INSERT INTO `language` VALUES ('fb-LT', 'fb', 'lt', 'Leet Speak', 'Leet Speak', '0');
INSERT INTO `language` VALUES ('fi-FI', 'fi', 'fi', 'Suomi', 'Finnish', '0');
INSERT INTO `language` VALUES ('fo-FO', 'fo', 'fo', 'Føroyskt', 'Faroese', '0');
INSERT INTO `language` VALUES ('fr-CA', 'fr', 'ca', 'Français (Canada)', 'French (Canada)', '0');
INSERT INTO `language` VALUES ('fr-FR', 'fr', 'fr', 'Français (France)', 'French (France)', '1');
INSERT INTO `language` VALUES ('fy-NL', 'fy', 'nl', 'Frysk', 'Frisian', '0');
INSERT INTO `language` VALUES ('ga-IE', 'ga', 'ie', 'Gaeilge', 'Irish', '0');
INSERT INTO `language` VALUES ('gl-ES', 'gl', 'es', 'Galego', 'Galician', '0');
INSERT INTO `language` VALUES ('he-IL', 'he', 'il', '‏עברית‏', 'Hebrew', '0');
INSERT INTO `language` VALUES ('hi-IN', 'hi', 'in', 'हिन्दी', 'Hindi', '0');
INSERT INTO `language` VALUES ('hr-HR', 'hr', 'hr', 'Hrvatski', 'Croatian', '0');
INSERT INTO `language` VALUES ('hu-HU', 'hu', 'hu', 'Magyar', 'Hungarian', '0');
INSERT INTO `language` VALUES ('hy-AM', 'hy', 'am', 'Հայերեն', 'Armenian', '0');
INSERT INTO `language` VALUES ('id-ID', 'id', 'id', 'Bahasa Indonesia', 'Indonesian', '0');
INSERT INTO `language` VALUES ('is-IS', 'is', 'is', 'Íslenska', 'Icelandic', '0');
INSERT INTO `language` VALUES ('it-IT', 'it', 'it', 'Italiano', 'Italian', '0');
INSERT INTO `language` VALUES ('ja-JP', 'ja', 'jp', '日本語', 'Japanese', '0');
INSERT INTO `language` VALUES ('ka-GE', 'ka', 'ge', 'ქართული', 'Georgian', '0');
INSERT INTO `language` VALUES ('km-KH', 'km', 'kh', 'ភាសាខ្មែរ', 'Khmer', '0');
INSERT INTO `language` VALUES ('ko-KR', 'ko', 'kr', '한국어', 'Korean', '0');
INSERT INTO `language` VALUES ('ku-TR', 'ku', 'tr', 'Kurdî', 'Kurdish', '0');
INSERT INTO `language` VALUES ('la-VA', 'la', 'va', 'lingua latina', 'Latin', '0');
INSERT INTO `language` VALUES ('lt-LT', 'lt', 'lt', 'Lietuvių', 'Lithuanian', '0');
INSERT INTO `language` VALUES ('lv-LV', 'lv', 'lv', 'Latviešu', 'Latvian', '0');
INSERT INTO `language` VALUES ('mk-MK', 'mk', 'mk', 'Македонски', 'Macedonian', '0');
INSERT INTO `language` VALUES ('ml-IN', 'ml', 'in', 'മലയാളം', 'Malayalam', '0');
INSERT INTO `language` VALUES ('ms-MY', 'ms', 'my', 'Bahasa Melayu', 'Malay', '0');
INSERT INTO `language` VALUES ('nb-NO', 'nb', 'no', 'Norsk (bokmål)', 'Norwegian (bokmal)', '0');
INSERT INTO `language` VALUES ('ne-NP', 'ne', 'np', 'नेपाली', 'Nepali', '0');
INSERT INTO `language` VALUES ('nl-NL', 'nl', 'nl', 'Nederlands', 'Dutch', '0');
INSERT INTO `language` VALUES ('nn-NO', 'nn', 'no', 'Norsk (nynorsk)', 'Norwegian (nynorsk)', '0');
INSERT INTO `language` VALUES ('pa-IN', 'pa', 'in', 'ਪੰਜਾਬੀ', 'Punjabi', '0');
INSERT INTO `language` VALUES ('pl-PL', 'pl', 'pl', 'Polski', 'Polish', '0');
INSERT INTO `language` VALUES ('ps-AF', 'ps', 'af', '‏پښتو‏', 'Pashto', '0');
INSERT INTO `language` VALUES ('pt-BR', 'pt', 'br', 'Português (Brasil)', 'Portuguese (Brazil)', '0');
INSERT INTO `language` VALUES ('pt-PT', 'pt', 'pt', 'Português (Portugal)', 'Portuguese (Portugal)', '0');
INSERT INTO `language` VALUES ('ro-RO', 'ro', 'ro', 'Română', 'Romanian', '0');
INSERT INTO `language` VALUES ('ru-RU', 'ru', 'ru', 'Русский', 'Russian', '0');
INSERT INTO `language` VALUES ('sk-SK', 'sk', 'sk', 'Slovenčina', 'Slovak', '0');
INSERT INTO `language` VALUES ('sl-SI', 'sl', 'si', 'Slovenščina', 'Slovenian', '0');
INSERT INTO `language` VALUES ('sq-AL', 'sq', 'al', 'Shqip', 'Albanian', '0');
INSERT INTO `language` VALUES ('sr-RS', 'sr', 'rs', 'Српски', 'Serbian', '0');
INSERT INTO `language` VALUES ('sv-SE', 'sv', 'se', 'Svenska', 'Swedish', '0');
INSERT INTO `language` VALUES ('sw-KE', 'sw', 'ke', 'Kiswahili', 'Swahili', '0');
INSERT INTO `language` VALUES ('ta-IN', 'ta', 'in', 'தமிழ்', 'Tamil', '0');
INSERT INTO `language` VALUES ('te-IN', 'te', 'in', 'తెలుగు', 'Telugu', '0');
INSERT INTO `language` VALUES ('th-TH', 'th', 'th', 'ภาษาไทย', 'Thai', '0');
INSERT INTO `language` VALUES ('tl-PH', 'tl', 'ph', 'Filipino', 'Filipino', '0');
INSERT INTO `language` VALUES ('tr-TR', 'tr', 'tr', 'Türkçe', 'Turkish', '0');
INSERT INTO `language` VALUES ('uk-UA', 'uk', 'ua', 'Українська', 'Ukrainian', '0');
INSERT INTO `language` VALUES ('vi-VN', 'vi', 'vn', 'Tiếng Việt', 'Vietnamese', '0');
INSERT INTO `language` VALUES ('xx-XX', 'xx', 'xx', 'Fejlesztő', 'Developer', '0');
INSERT INTO `language` VALUES ('zh-CN', 'zh', 'cn', '中文(简体)', 'Simplified Chinese (China)', '0');
INSERT INTO `language` VALUES ('zh-HK', 'zh', 'hk', '中文(香港)', 'Traditional Chinese (Hong Kong)', '0');
INSERT INTO `language` VALUES ('zh-TW', 'zh', 'tw', '中文(台灣)', 'Traditional Chinese (Taiwan)', '0');
