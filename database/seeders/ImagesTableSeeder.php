<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ImagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('images')->delete();
        
        \DB::table('images')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'title' => 'Sleepy dog',
                'prompt' => 'Sleepy dog',
                'path' => 'images/img-t8mBQoWIFcYfCGBzWPCmWsGe.png',
                'is_public' => false,
                'created_at' => '2025-03-07 05:14:48',
                'updated_at' => '2025-03-07 05:14:48',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            1 => 
            array (
                'id' => 4,
                'user_id' => 1,
                'title' => 'Ancient mage ',
                'prompt' => 'The magician casts a spell. Ancient Mayan setting.',
                'path' => 'images/img-9yh2i5Ieogr3w8wOYcZqOoR1.png',
                'is_public' => false,
                'created_at' => '2025-03-08 01:13:36',
                'updated_at' => '2025-03-08 01:13:36',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            2 => 
            array (
                'id' => 5,
                'user_id' => 1,
                'title' => 'Warrior',
                'prompt' => 'A fearsome warrior in an ancient Mayan setting brandishes a weapon',
                'path' => 'images/img-RyjLSrQvxOoc31CVNiD3gNa0.png',
                'is_public' => false,
                'created_at' => '2025-03-08 01:23:17',
                'updated_at' => '2025-03-08 01:23:17',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            3 => 
            array (
                'id' => 7,
                'user_id' => 1,
                'title' => 'Knife',
                'prompt' => 'Mayan obsidian knife close up',
                'path' => 'images/img-jUzXIBmOJODsOV1lxEG1mFAZ.png',
                'is_public' => false,
                'created_at' => '2025-03-08 02:26:26',
                'updated_at' => '2025-03-08 02:26:26',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            4 => 
            array (
                'id' => 8,
                'user_id' => 1,
                'title' => 'City',
                'prompt' => 'Bird\'s eye view of ancient Mayan city',
                'path' => 'images/img-SzjWy9kRS705ieGB9bjmzXUA.png',
                'is_public' => false,
                'created_at' => '2025-03-08 02:30:49',
                'updated_at' => '2025-03-08 02:30:49',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            5 => 
            array (
                'id' => 9,
                'user_id' => 1,
                'title' => 'Tezcatlipoca',
                'prompt' => 'Ancient Mayan deity Tezcatlipoca depicted on a wall',
                'path' => 'images/img-GRHJJldQToPn7ASWMGzCyrNs.png',
                'is_public' => false,
                'created_at' => '2025-03-08 02:36:34',
                'updated_at' => '2025-03-08 02:36:34',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            6 => 
            array (
                'id' => 15,
                'user_id' => 1,
                'title' => 'Masters',
                'prompt' => 'Epic fantasy heroes play thoughtful chess at a large chessboard',
                'path' => 'images/img-RF1LWsK3uXn0tmxr9Z9xVYhN.png',
                'is_public' => false,
                'created_at' => '2025-03-08 03:46:07',
                'updated_at' => '2025-03-08 03:46:07',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            7 => 
            array (
                'id' => 13,
                'user_id' => 1,
                'title' => 'Discussion',
                'prompt' => 'A team of fantasy adventurers stand in front of a massive stone door in a cliff, discussing how to open it.',
                'path' => 'images/img-jDvB1lAa8HbQZ256rPSXxoGm.png',
                'is_public' => true,
                'created_at' => '2025-03-08 03:34:51',
                'updated_at' => '2025-03-09 02:09:49',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            8 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'title' => 'Crash',
                'prompt' => 'An Imperial Star Wars cruiser crashed in the middle of an endless alien desert',
                'path' => 'images/img-wFoEGgzbmCGfnawfZHj1VkDu.png',
                'is_public' => true,
                'created_at' => '2025-03-07 05:46:49',
                'updated_at' => '2025-03-09 02:10:12',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            9 => 
            array (
                'id' => 16,
                'user_id' => 1,
                'title' => 'Woman - 1',
                'prompt' => 'Portrait of a strong-willed young woman in fantasy style',
                'path' => 'images/img-Cc0MqStl3k7tpK8KXxPmn4JK.png',
                'is_public' => false,
                'created_at' => '2025-03-12 04:58:19',
                'updated_at' => '2025-03-12 04:58:19',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            10 => 
            array (
                'id' => 17,
                'user_id' => 1,
                'title' => 'Man - 1',
                'prompt' => 'Close-up portrait of a man in diesel punk style',
                'path' => 'images/img-IOyPYkqZeSYkiI3tlm1fx85T.png',
                'is_public' => false,
                'created_at' => '2025-03-12 04:59:55',
                'updated_at' => '2025-03-12 04:59:55',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            11 => 
            array (
                'id' => 18,
                'user_id' => 1,
                'title' => 'Alien - 1',
                'prompt' => 'Close-up portrait of an alien hero in the style of Star Wars',
                'path' => 'images/img-bXS4C0D2rpWtdG9O6WZRpJz1.png',
                'is_public' => false,
                'created_at' => '2025-03-12 05:02:09',
                'updated_at' => '2025-03-12 05:02:09',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            12 => 
            array (
                'id' => 19,
                'user_id' => 1,
                'title' => 'Cyberpunk Samurai',
                'prompt' => 'A cyberpunk samurai with glowing neon tattoos, wearing a futuristic armored kimono, standing in the rain with a holographic katana. The neon city lights reflect in their cybernetic eyes, blending traditional Japanese aesthetics with high-tech sci-fi elements.',
                'path' => 'images/img-SI9gDRwxwg70QerwyqNgsXE1.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:00:33',
                'updated_at' => '2025-03-16 17:00:33',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            13 => 
            array (
                'id' => 20,
                'user_id' => 1,
                'title' => 'Steampunk Explorer',
                'prompt' => 'A steampunk adventurer with brass goggles, a mechanical arm, and a leather trench coat covered in clockwork gears. His airship floats in the background, and he holds a glowing map with mysterious symbols, ready for his next grand expedition.',
                'path' => 'images/img-RLs3MFMSaO20EXf8F4YbhYlg.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:02:13',
                'updated_at' => '2025-03-16 17:02:13',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            14 => 
            array (
                'id' => 21,
                'user_id' => 1,
                'title' => 'Celestial Warrior',
                'prompt' => 'A celestial warrior with golden wings, clad in radiant silver armor, holding a divine flaming sword. Her eyes glow with cosmic energy, and behind her, the vast expanse of a nebula-filled galaxy stretches infinitely.',
                'path' => 'images/img-PpWnX1z0tpKy9pLF27QvEwBQ.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:04:07',
                'updated_at' => '2025-03-16 17:04:07',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            15 => 
            array (
                'id' => 22,
                'user_id' => 1,
                'title' => 'Post-Apocalyptic Scavenger',
                'prompt' => 'A rugged survivor in a post-apocalyptic wasteland, wearing a tattered hood and gas mask. Their armor is made from scavenged metal plates, and they clutch a makeshift energy rifle. Dust and smoke fill the air, with ruined skyscrapers in the background.',
                'path' => 'images/img-JEjAq6c10uPtjjQYNAv1Iqvi.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:04:40',
                'updated_at' => '2025-03-16 17:04:40',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            16 => 
            array (
                'id' => 23,
                'user_id' => 1,
                'title' => 'Ancient Sorcerer',
                'prompt' => 'A wise and mysterious sorcerer with a long silver beard, deep violet robes covered in arcane runes, and piercing glowing eyes. He holds an ancient spellbook, and swirling magical energy crackles around his fingertips.',
                'path' => 'images/img-2A1a2T6qzgy4tKurT93PgS9x.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:05:02',
                'updated_at' => '2025-03-16 17:05:02',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            17 => 
            array (
                'id' => 24,
                'user_id' => 1,
                'title' => 'Mythical Forest Guardian',
                'prompt' => 'A mystical humanoid creature covered in moss and vines, with antlers made of twisting tree branches. Her glowing green eyes shimmer like emeralds, and fireflies float around her as she stands deep in an enchanted forest.',
                'path' => 'images/img-y49h0VyOBMTX8rez3g2Z9fvJ.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:05:28',
                'updated_at' => '2025-03-16 17:05:28',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            18 => 
            array (
                'id' => 25,
                'user_id' => 1,
                'title' => 'Retro-Futuristic Space Pilot',
                'prompt' => 'A retro-futuristic space pilot from the 1950s sci-fi era, wearing a sleek metallic spacesuit with a glass dome helmet. He has a confident smirk, a laser pistol holstered at his side, and a gleaming chrome spaceship behind him, set against the backdrop of a ringed planet.',
                'path' => 'images/img-d36K1u4lpXjemdsYdlA2AV5u.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:05:49',
                'updated_at' => '2025-03-16 17:05:49',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            19 => 
            array (
                'id' => 26,
                'user_id' => 1,
                'title' => 'Norse Battle Goddess',
                'prompt' => 'A fierce Norse warrior goddess with intricate braids in her fiery red hair, wearing golden armor and a wolf-fur cloak. She holds a glowing runic axe, and a storm rages behind her as she stands on a Viking longship.',
                'path' => 'images/img-08aGJkdoL1k0KNUzfdzuuB6L.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:06:11',
                'updated_at' => '2025-03-16 17:06:11',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            20 => 
            array (
                'id' => 27,
                'user_id' => 1,
                'title' => 'Digital Dream Shaman',
                'prompt' => 'A futuristic shaman who exists between the digital and spiritual worlds, wearing a robe infused with glowing circuit patterns. His cybernetic face mask displays shifting ancient symbols, and holographic spirits swirl around him in a neon-lit temple.',
                'path' => 'images/img-9r83OpXCEmKrZTVOJKGOWle2.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:06:46',
                'updated_at' => '2025-03-16 17:06:46',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            21 => 
            array (
                'id' => 28,
                'user_id' => 1,
                'title' => 'Gothic Phantom Empress',
                'prompt' => 'A mysterious gothic empress in a dark, enchanted castle, wearing an elegant black silk dress with silver embroidery. Her piercing silver eyes glow faintly, and a spectral aura surrounds her. She stands near a grand candlelit throne, with ravens perched on twisted iron chandeliers above.',
                'path' => 'images/img-F3eS2yLINNjK8apl5sgchRWD.png',
                'is_public' => false,
                'created_at' => '2025-03-16 17:08:46',
                'updated_at' => '2025-03-16 17:08:46',
                'has_glitches' => false,
                'aspect' => 'square',
                'attempts' => 1,
            ),
            22 => 
            array (
                'id' => 31,
                'user_id' => 1,
                'title' => 'Explorer’s Journal Screen',
            'prompt' => 'An open adventurer’s journal on a rustic wooden desk, surrounded by ink bottles, quills, wax seals, and scattered old maps. The pages contain elegant handwritten notes, sketches of creatures, and cryptic symbols. A warm glow from a candle illuminates the scene, adding a vintage explorer’s touch. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-IX0k0DVIigBSbMzedmxKfOrV.png',
                'is_public' => false,
                'created_at' => '2025-03-16 18:51:22',
                'updated_at' => '2025-03-16 20:01:18',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            23 => 
            array (
                'id' => 33,
                'user_id' => 1,
                'title' => 'Seaside Shore Screen',
            'prompt' => 'A scenic view of a peaceful sandy beach with gentle waves touching the shore. Scattered seashells, driftwood, and small crabs add detail. The sky is painted in warm hues of a sunset, making the scene feel tranquil and inviting. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-ogTN2wRpCjSiGzSRl5PqBcSt.png',
                'is_public' => false,
                'created_at' => '2025-03-16 18:57:05',
                'updated_at' => '2025-03-16 20:01:04',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            24 => 
            array (
                'id' => 32,
                'user_id' => 1,
                'title' => 'Navigation Table Screen',
            'prompt' => 'A navigator’s wooden desk filled with a large parchment map, a brass compass, measuring tools, a spyglass, and various old navigation charts. The desk is illuminated by soft lantern light, creating a historical and adventurous atmosphere. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-Si1xF19CBF2Hr5LyNKx4gMGg.png',
                'is_public' => false,
                'created_at' => '2025-03-16 18:51:47',
                'updated_at' => '2025-03-16 20:01:11',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            25 => 
            array (
                'id' => 30,
                'user_id' => 1,
                'title' => 'Adventurer’s Inventory Screen',
            'prompt' => 'A detailed and atmospheric adventurer\'s inventory laid out on a wooden table. Includes a worn leather backpack, rolled-up maps, a compass, a dagger, potions in glass vials, gold coins, and an old lantern. The setting is dimly lit by candlelight, creating a sense of mystery and adventure. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-YIEDpwg9xGthqNEQRaGpk0TW.png',
                'is_public' => false,
                'created_at' => '2025-03-16 18:48:57',
                'updated_at' => '2025-03-16 20:01:25',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            26 => 
            array (
                'id' => 40,
                'user_id' => 1,
                'title' => 'Alchemy Workshop Screen',
            'prompt' => 'A mystical alchemist’s workshop with wooden shelves lined with potion bottles, bubbling cauldrons, ancient scrolls, and an open grimoire with arcane symbols. Dim lanterns and green magical light add an eerie yet fascinating atmosphere. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-2Khiebbkna4fhdunmPmVdjio.png',
                'is_public' => false,
                'created_at' => '2025-03-16 19:02:51',
                'updated_at' => '2025-03-16 19:59:52',
                'has_glitches' => true,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            27 => 
            array (
                'id' => 39,
                'user_id' => 1,
                'title' => 'Mountain Peak Camp Screen',
            'prompt' => 'A high-altitude adventurer’s camp set on a snowy mountain peak. A small tent, a firepit, climbing gear, and an ice axe stuck in the ground. Below, a breathtaking view of distant mountain ranges bathed in the first light of dawn. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-nRLcqTq8ROESplNwkt6NRNNX.png',
                'is_public' => false,
                'created_at' => '2025-03-16 19:01:26',
                'updated_at' => '2025-03-16 20:00:19',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            28 => 
            array (
                'id' => 38,
                'user_id' => 1,
                'title' => 'Desert Caravan Camp Screen',
            'prompt' => 'A nomadic caravan resting in the middle of a vast desert. Tents made of colorful fabrics, a campfire burning in the center, and camels resting nearby. The golden sand dunes stretch far into the horizon under a pinkish-orange sunset sky. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-wXvsgxnNHaAaTjQWOtkJbGmE.png',
                'is_public' => false,
                'created_at' => '2025-03-16 19:00:42',
                'updated_at' => '2025-03-16 20:00:31',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            29 => 
            array (
                'id' => 37,
                'user_id' => 1,
                'title' => 'Ancient Library Screen',
            'prompt' => 'A grand and mysterious library filled with towering bookshelves, ancient tomes, and glowing magical artifacts. Dust particles float in the dim golden light coming from chandeliers. An old wooden ladder leans against a shelf, inviting discovery. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-jolg3UHCTV9AW938tKvkDbrX.png',
                'is_public' => false,
                'created_at' => '2025-03-16 19:00:22',
                'updated_at' => '2025-03-16 20:00:39',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            30 => 
            array (
                'id' => 35,
                'user_id' => 1,
                'title' => 'Tavern Interior Screen',
            'prompt' => 'A cozy medieval tavern interior with wooden beams, a roaring fireplace, and rustic wooden tables. Shelves stacked with dusty bottles of ale, barrels in the corner, and dim candlelight add to the warm and lively ambiance. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-Ho17fDoVWcDCQMqP7XChQo7i.png',
                'is_public' => false,
                'created_at' => '2025-03-16 18:58:46',
                'updated_at' => '2025-03-16 20:00:50',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
            31 => 
            array (
                'id' => 34,
                'user_id' => 1,
                'title' => 'Jungle Trail Screen',
            'prompt' => 'A dense jungle pathway covered in mist, surrounded by towering ancient trees, thick vines, and exotic flowers. Sunlight filters through the dense canopy, casting a golden glow over the moss-covered stones and leaves. The path looks like it leads to something mysterious ahead. Aspect ratio: portrait (tall image, 1024x1792)',
                'path' => 'images/img-RO6MDQHQeXuIyG7ek5dLYtYh.png',
                'is_public' => false,
                'created_at' => '2025-03-16 18:57:25',
                'updated_at' => '2025-03-16 20:00:57',
                'has_glitches' => false,
                'aspect' => 'portrait',
                'attempts' => 1,
            ),
        ));
        
        
    }
}