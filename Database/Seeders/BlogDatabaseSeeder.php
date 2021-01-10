<?php

namespace Modules\Blog\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Entities\Blog;
use Modules\Filemanager\Entities\Filemanager;
use Modules\Magazine\Entities\Magazine;
use Modules\Permission\Entities\Permission;
use Modules\User\Entities\User;

class BlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data = [
          [
              'title' => 'ISSUE NO. 123, AN INTERVIEW WITH PROFESSOR LEGENHAUSEN',
              'subtitle' => 'ISSUE NO. 123, AN INTERVIEW WITH PROFESSOR LEGENHAUSEN',
              'summary' => 'In this issue, Eight Magazine is proud to present an interview with Professor Legenhausen focusing on the relation between Shīʿīsm and rationalism and related topics such as philosophy, theology, ethics, and jurisprudence.',
              'content' => '
Gary Carl (Mohammad) Legenhausen (b. 1953, New York City) is an American philosopher who teaches at IKI, the Shīʿa Islamic religious educational institute in Iran. Brought up as a Catholic, he abandoned Christianity in 1983 shortly after beginning his academic studies at the State University of New York at Albany. In 1979, he became acquainted with Islam through Muslim students at Texas Southern University, where he taught from 1979 to 1989.  An exciting news for us was that the Professor has been recently a virtual servant (khādim) of Imam Riḍā’s (P.B.U.H.) holy Shrine.',
              "tags" => 'interview eightmag issue-123',
              "magazine_id" => Magazine::inRandomOrder()->first()->id,
              "author_id" => User::where("role", "admin")->first()->id,
              'photo_id' => Filemanager::whereName('blog1')->first()->id,
          ], [
                'title' => 'ISSUE NO. 124, T FOR TWELVER',
                'subtitle' => 'ISSUE NO. 124, T FOR TWELVER',
                'summary' => 'Our followers/adherents are the obedient ones to our orders/instructions, the ones who accept our saying; the conflicting ones amongst our enemies; therefore, whoever is not like this, then has nothing to do with us.',
                'content' => '
                شِيعَتُنَا اَلْمُسَلِّمُونَ لِأَمْرِنَا اَلْآخِذُونَ بِقَوْلِنَا اَلْمُخَالِفُونَ لِأَعْدَائِنَا فَمَنْ لَمْ يَكُنْ كَذَلِكَ فَلَيْسَ مِنَّا.

Our followers/adherents are the obedient ones to our orders/instructions, the ones who accept our saying; the conflicting ones amongst our enemies; therefore, whoever is not like this, then has nothing to do with us.

        It may seem easy to claim to be a true follower of the infallible Imams (P.B.U.T.) but we are expected to practically prove it. Imam Riḍā (P.B.U.H.) has mentioned three of the most important requirements that one must meet in order to be able to claim to be a follower of one’s Imams (P.B.U.T.).

A true follower-otherwise known as a “Shīʿa” in the Islamic literature-should solely be obedient toward their Imam’s (P.B.U.H.) instructions. But this would not be possible if a person is actually a follower of one’s own desires or the desires of those who may have an influence or authority over them. Therefore, the more one shows obedience, the more one can consider oneself to be a true follower.

Moreover, the rich traditions and narrations of our Infallibles (P.B.U.T.) are a source of guidance by which one can distinguish the true path to being a loyal and sincere follower. Therefore, a true follower is expected to learn, accept, and treasure them throughout their life.

Finally, God has ordered in the holy Qur’an that the believers must not befriend His enemies. On the other hand, our beloved Infallibles (P.B.U.T.) are the very epitome of the friends of God. Consequently, deep in one’s heart a follower should feel love for them and hate for their enemies who are of course the enemies of God. This love will help draw one toward God and His beacons of light and the hate will help draw one away from God’s enemies.

May God help us prove ourselves to be true followers of our beloved Infallibles (P.B.U.T.) while we have the chance!',
                "tags" => 'shia shism eightmag issue-124',
                "magazine_id" => Magazine::inRandomOrder()->first()->id,
                "author_id" => User::where("role", "admin")->first()->id,
                'photo_id' => Filemanager::whereName('blog2')->first()->id,
            ],
        ];

        Blog::insert($data);


        Blog::factory()->count(4)->create();
    }
}
