<?php

class IndexController extends Controller
{

public function index()
{

    $this->view->render('pocetnastranica',[ 
        'podaci'=>Recepti::pretraga('','1'),
            'stranica' => '1',
            'uvjet' => '',
            'stranicenje' => Recepti::stranicenje('')
    ]);
}

public function login()
{
    $this->view->render('login',
    [
        'message'=> 'Unesite pristupne podatke ili izvršite registraciju',
        'email'=>''
    ]);
} 

public function AuthorizationLogin()
{
    if (!isset($_POST['email']) || !isset($_POST['password']))
    {
        $this->view->render('login',
        [
            'message'=>'Nisu postavljeni pristupni podaci',
            'email'=>''
        ]);
        return;
    }
    if (trim($_POST['email'])==='' || trim($_POST['password'])==='')
    {
        $this->view->render('login',
        [
            'message'=>'Pristupni podaci obavezni',
            'email'=>$_POST['email']
        ]);
        return;
    }
   $vezabaza= Database::getInstanca();
    $izraz=$vezabaza->prepare('select * from registracija where email=:email;');
    $izraz->execute(['email'=>$_POST['email']]);
    $rezultat=$izraz->fetch ();

    if ($rezultat==null)
    {
        $this->view->render('login',
        [
            'message'=>'Ne postojeći korisnik',
            'email'=>$_POST['email']
        ]);
        return;
    }

    if (!password_verify($_POST['password'], $rezultat->password))
    {
        $this->view->render('login',
        [
            'message'=>'Neispravan email ili lozinka',
            'email'=>$_POST['email']
        ]);
        return;
    }
    unset ($rezultat->password);
    $_SESSION['registracija']=$rezultat;
     $npc=new NadzornaplocaController();
     $npc->index();

}
public function logout()
{
    unset ($_SESSION['registracija']);
    session_destroy();
    $this->index();
}

public function era()
{
    $this->view->render('era');
}


/*
public function refreshtableregistracija()
{
        $vezabaza= Database::getInstanca();
        $izraz=$vezabaza->prepare('
    use ki355633_jelasvijeta;
    drop table if exists registracija;
    drop table if exists recepti;
    drop table if exists kategorija;
    drop table if exists receptiregistracija;

    create table registracija(
    sifra       int not null primary key auto_increment,
    username     varchar(50) not null,
    email       varchar(50) not null,
    password     char(60) not null,
    ime         varchar(50) not null,
    prezime     varchar(50) not null
    );
    insert into registracija values 
    (null, \'Tomislav\', \'zidarto@hotmail.com\',
    \'$2y$10$1ObtPOr7unAMR6Zpo462Kuea4FkJSy3lLAb1eth86Xa7Kp/gcBhJq\',
    \'Tomislav\', \'Zidar\');
    insert into registracija values 
    (null, \'AdminZidar\', \'tozidar@gmail.com\',
    \'$2y$10$b0In9IcFO63vOcA68CAlnemPx8u8lH1z6/1WFcYyFtfLoXiQ2r4DK\',
    \'Admin\', \'Zidar\');

    create table receptiregistracija(
    sifra int not null primary key auto_increment,
    recepti int not null,
    registracija int not null

    );

    create table recepti(
    sifra int not null primary key auto_increment,
    naziv varchar(255) not null,
    kolicina varchar(100),
    sastojci text not null,
    opis text not null,
    kategorija int not null
    );
    insert into recepti (sifra,naziv,kolicina,sastojci,opis,kategorija)
    values
    
     (null, \'Domaca goveda juha \', \'6 osobe \', \'600 g govedine za juhu1, 2 l vode, 200 g crvene mrkve, 100 g žute mrkve, 100 g korijena celera, 100 g korijena peršina\',
    \'Oprano meso stavite u mlaku vodu i čim voda zakipi, dodajte sol, očišćeno korjenasto povrće, nekoliko zrna papra i luk, koji ste prethodno popekli u tavici. Nakon toga, dodajte list kupusa i kelja, grančicu celera i Vegetu pa poklopite. Kad juha ponovo zakipi, smanjite temperaturu pa lagano kuhajte oko 2 sata. Meso izvadite, juhu procijedite pa u nju ukuhajte domaće rezance.
    Posluživanje
    Pospite nasjeckanim peršinom i poslužite.
    Savjet
    Da biste dobili bistru juhu, potrebno ju je kuhati na laganoj vatri.
    Napomena
    Ovo možete i zamrznuti
    Zamrzavanje: Juhu pripremite prema receptu, ali nemojte ukuhati rezance. Procijeđenu juhu ohladite i skinite suvišnu masnoću. Rasporedite je u odgovarajuće posude s poklopcem ili plastične vrećice i zamrznite. 
    Odmrzavanje: Posude ili vrećice s juhom kratko uronite u vruću vodu kako bi se zamrznuta juha odvojila od stijenki. Zatim je prebacite u posudu s malo vode i zagrijavajte na laganoj vatri dok se ne otopi. Ukuhajte rezance ili drugu tjesteninu. Trajnost: 90 dana.\',
    2),

     (null, \'Veganska francuska salata\', \'4 osobe \', \'Domaća tofuneza Sastojci: 300 gr bijelog tofua, sok 1 limuna, 1 žličica senfa, 1 žličica šećera, sol, papar, 1 žlica ulja. Ako ste u gužvi, možete kupiti zamrznuti miks sjeckanog povrća za francusku, ali ako imate volje i vremena, naravno da će biti puno ukusnije ako skuhate kockice domaćeg, svježeg povrća. Treba vam oko 200 gr graška, 200 gr mrkve, 200 gr krumpira. Dalje: 2 kisela krastavca, pola kisele jabuke, oko 300 – 500 ml veganske majoneze ili tofuneza, sol, papar, sok od 1 limuna, 1 žličica senfa, peršin, kriške limuna i listovi peršina za ukras.\',
    \'Postupak: Povrće nasjeckati na kockice i skuhati u slanoj vodi pazeći da se ne raskuha (da ostane čvrsto). Nasjeckati krastavce i jabuke na kockice, usitniti peršin. Pomiješajte prohlađeno povrće i ostale sastojke u zdjeli, poravnajte i ukrasite listovima peršina i kriškama limuna – poslužite rashlađeno. Postupak domaća tofuneza: Sve sastojke promiješati u blenderu. Možete dodati malo vode.\',
    4),
     (null, \'Orijentalna salata \', \'1 osobe \', \'2-3 mrkve( ca.150gr), 1vk suhih grožđica, 1vk pinjola, 1vk svjezeg lista persina Preljev: 2vk maslinovog ulja , 2vk jabukovog sirćeta, 1ck meda ili bagremovog sirupa, 1/2ck curry /kari u prahu morske soli\',
    \'Pinjole preprzite na suhoj tavi da lijepo porumene i ostavite sa strane. Peršin operite i sitno nasjeckajte. Mrkvu ogulite, pa je nozicem za guljenje narezite na trakice, okrecuci mrkvu citavo vrijeme da dobijete tanke uske trakice  . Stavite u zdjelu i dodajte pinjole, peršin i grozdjice. Za preljev dobroro izmjesajte ulje, sirce, med i curry.
    Prelijte preko mrkve, posolite svjeze mljevenom morskom soli  i sve dobro izmjesajte.  Salatu ostavite nakratko da ostoji i uzivajte u njoj.
    Posluživanje: Salatu mozete posluziti i toplu, tako sto zagrijete mrkvu sa malo ulja u pecnici na 150*C  5 minuta i onda dodate ostale sastojke kao sto pise u rezeptu.\',
    5),

    (null, \'Tjestenina s meksickim bolonjezom \', \'4 osobe \', \'tjestenina po želji, mljeveno meso, pasirana rajcica, kukuruz šecerac (150 g), crveni grah , glavica luka ,mix zacina (sol, papar, vegeta i paprika) ,sir\',
    \'Tjesteninu pravim na sljedeci nacin: Zagrijem u kuhalu (oko 1L) vode. Kad prokuha voda, u lonac stavim žlicu soli i ulja, tjesteninu i prokuhanu vodu. Oko 8-10 min tijesto se kuha (ovisi o tjestenini). Nakon toga ostavim tijesto u poklopljenom loncu. Nakon 5 min ocjedim ga i spremno je za jesti.
    Dok se tijesto kuha, pravim pripreme za bolonjez. Ugrijem ulje u tavi i stavim luk da se malo dinsta. Kada luk poprimi boju, stavljam mljeveno meso. Kada poprimi sivkastu boju, stavljam 2 caše vode i puštam da se meso krcka u tome. Nakon toga stavljam jednu pasiranu rajcicu i puštam da se krcka meso. 
    Dodajem kukuruza, crvenog graha, mix zacina (u caši izmiješam žlicu soli, papra, vegete i paprike). U caši izmiješam 2-3 žlice brašne s toplom vodom i zamutim. Dodajem u umak i lagano miješam dok se ne zgusne. Kada prokljuca, dodam origana i jelo je spremno za serviranje.\',
    3),
    (null, \'Croissants \', \'10 osoba \',\'500 g glatkog brašna, 200 ml mlijeka, 200 ml ulja, 2 jaja, 2 žlicice soli, 1 kvasac, malo vode oko 50ml, 250 g margarina ili maslaca, Za punjenje: sir, šunka, nutela, cokolada, svježi sir..ili ako imate nešto drugo u planu isto tako slobodno stavite, Za premazivanje: 1 žutanjak, sol, sjemenke sezama, lana, suncokreta ili chia sjemenke \',
    \'Kvasac, 1 žlicu šecera i ugrijano toplo mlijeko umutite i ostavite da se digne. U zdjeli dobro izmiješajte brašno i sol te dodajte dignuti kvasac, toplo mlijeko koje ste izmiješali s uljem, vodom i jajetom. Umijesite glatko tijesto. Tijesto ostavite da se diže nekih 45 minuta. Kada se tijesto diglo, podijelite da ga na 8 loptica. Izvaljajte tijesto u krug promjera i na izvaljano tijesto  poslažite tanko rezane kriške maslaca ili margarina(ja nekad znam smrznuti margarina i onda ga ribam jeer mi je tako lakše...a nekad znam otopiti margarina pa tijesto premažem otopljenim margarinom...kako vam je lakše tako je najbolje da radite), pa na njega složite sljedeci krug. Radite tako sve dok ne dodete do osmog kruga tijesta kojeg stavljate zadnjeg i na njega ne stavljate margarin. Ja ponekad znam raditi samo po 4 loptice jer mi je kasnije lakše razvaljati...pa onda potom opet s 4 loptice, jer kada ih je 8 ispadne jako veliko tijesto koje je malo teže razvaljati ako nemate veliku površinu. Tako posloženo tijesto ostavite još nekih 15 minuta da se malo digne. Zatim ga razvaljajte  do debljine ispod jednog centimentra. Prerežite prvo na 4 dijela, pa svaku cetvrtinu još na 4 dijela ako želite vece kroasane ili na 6 dijelova ako želite manje kroasane. Kroasane punite nadjevom koji želite, ili ih ostavite prazne i zarolajte u pužice, odnosno, u formu kroasana. ukoliko ih punite dobro pripazite da ih dobro zarolate kako nadjev nebi izašao van. Složite na pleh koji se namastili. Premažite ih žutanjkom i malo posipajte sa soli i ukoliko želite sjemenkama. Ovaj put ja sam ih posipala sa sjemenkama sezama, no znam koristiti i lanene sjemenke. Pecite ih na 180C oko 15-20 minuta i pripazite da ne izgore. Dovoljno je da porumene s gornje i donje strane i onda su gotovi. \'
    ,1),
    (null, \'Ledena bajadera \', null,\'KORA: 100 gr.margarine, 3 kašike šecera, 100 gr.tamne cokolade, 2 kašike kakaa u prahu, 300 gr. mlevene Plazme, 100 gr.oraha(seckanih i mlevenih), 2dcl mlijeka, FIL: 4 dcl mleka, 1 vanil puding, 100 gr.margarina, 3 kašike šecera, 1 bourbon vanil šecer, GLAZURA: 100 gr. tamne cokolade(može i malo više), malo ulja \',
    \'Otopiti 100gr.tamne cokolade.Posebno umutiti 1oogr.omekšale margarine sa 3 kašike šecera i dodati otopljenu cokoladu. Posebno sjediniti 300 gr.mlevene Plazme,2dcl mleka,2 kašike kakava u prahu i 100 gr.oraha(seckanih i mlevenih) i to dobro izmešati. To sjediniti sa masom od coklade i maslaca i izmešati u kompaktnu smesu. U manju šerpu sipati 3dcl mleka,dodati 3 kašike šecera i staviti da provri lagano.Za to vreme dok ne provri 1 prašak za puding od vanile smešati sa 1 dcl mleka,u to dodati 1 bourbon vanil šecer...to smešati i sipati u zavrelo mleko te na brzo pokuhati  i skloniti na hladno da se ohladi. Kad se ohladi,smešati ga sa 100 gr.omekšale margarine. U manju tepsiju 20 /33(otprilike) staviti smesu(koru) i poravnati...preko toga staviti fil od pudingai poravnati. Na kraju preko fila staviti glazuru koju napraviti da nad parom otopiš 100 gr.tamne cokolade i malo ulja. Može se ukrasiti il mlevenim orasima il cokoladnim mrvicama. Staviti na hladno da malo odstoji i da glazura malo stvrdne.  Prijatno!! \'
    ,6);

    create table kategorija(
    sifra int not null primary key auto_increment,
    katjela varchar(100) not null
    );

    insert into kategorija (sifra,katjela)
    values
    (1, \'Predjelo\'),
    (2, \'Juha\'),
    (3, \'Meso i riba\'),
    (4, \'Vegetarijansko jelo\'),
    (5, \'Salata\'),
    (6, \'Slastica\');

    alter table  receptiregistracija add foreign key (registracija) references registracija(sifra);
    alter table  receptiregistracija add foreign key (recepti) references recepti(sifra);
    alter table recepti add foreign key (kategorija) references kategorija(sifra);
    ');
    $izraz->execute();
           echo 'ok'; 
}
*/
}
