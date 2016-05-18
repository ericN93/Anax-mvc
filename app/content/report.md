#Krav 1, 2, 3: Grunden

###Webbsidan skall skyddas av inloggning. Det skall gå att skapa en ny användare. Användaren skall ha en profil som kan uppdateras. Användarens bild skall vara en gravatar.

I kmom05 skulle man göra en egen liten modul som skulle kunna funka med Anax-MVC, och då gjorde jag en liten modul för att kunna sköta inloggning av användare så där använde jag mig lite av den i projektet. Det var bara en liten modul så det var en hel del fixa så det funkade med projektet. Hur jag gick tillväga för att göra denna modulen till projektet var att jag började göra en UsersController med modellen User, därefter gjorde jag UserformController som då kan bygga formulär för  att lägga till en ny användare, uppdatera användaren samt logga in en användare. Jag gjorde så att både lägga till en ny användare samt uppdatera en användare ligger i en fil och för att urskilja vilket formulär som ska göras så skickar jag med id och i filen så kollar den om id är satt eller inte, om den är satt så kör den "uppdatera"-formuläret annars "lägg till"-formuläret den kollar detta med denna if-satsen (if(!isset($id))). Om användaren ska uppdateras skickas  "user" och därav använder formuläret värden från "user" för att på de befintliga värdena från den användaren, jag har löst att användarna kan inte ha samma email eller användarnamn och detta gjorde jag för att annars hade det krockat flera gånger i programmet när jag använder min funktion för att få användare på email eller acronym. Om fältet "password" är ifyllt uppdaterar den lösenordet annars gör den inte det. I mitt program så är jag ganska beroende av att kolla vem som är inloggad, och därför var jag även tvungen att fixa så att när man uppdaterar en användare så startar den om sessionen så att sessionen är uppdaterad för annars hade användaren inte haft tillgång till att redigera info eftersom jag kollar om användaren som ligger i sessionen har tillhörighet att röra vissa saker.

Sedan har jag att loginAction är en egen funktion och kallar på sitt egna formulär, när denn funktionen körs.

Kravet med att man ska ha en användar bild som ska vara gravatar hade jag redan kollat på i kmom04 så det hade jag redan löst till projektet. Det löste man med denna enkla rad:

$gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '.jpg?s=80';

Sedan placerar man bara variablen $gravatar i där man vill ha den.(I detta fallet låg raden på profil sidan så då tar jag emialen via $user variablen).

###Webbplatsen skall ha en förstasida, en sida för frågor, en sida för taggar och en sida för användare. Precis som Stack Overflow har. Dessutom skall det finnas en About-sida med information om webbplatsen och dig själv.
Jag började detta projekt med att göra detta kravet, dvs jag gjorde själva sklettet först för att få en överblick an min hemsida. Jag skrev inget i filerna då utan ville bara få det att funka att man kan navigera sig runt på hemsidan och då gjorde jag navbaren, hade ingen riktig style ännu så det såg inte så bra ut men det löste jag senare under processen av projektet. Sidan "Tags" använde jag mig lite av font-awesome för att få de tlite snyggare.

###En användare kan ställa frågor, eller besvara dem. Alla inlägg som en användare gör kan kopplas till denna. Klickar man på en användare så ser man vilka frågor som användaren ställt och vilka frågor som besvarats.
På detta kravet började jag med att göra min QuestionController med en funktion för att skriva ut alla frågor i databasen, detta testade jag sen med att skriva in några frågor i databasen för att se om det funkade. Sedan gjorde jag en QuestionFormController där jag har två funktioner, en för att lägga till en fråga sedan en för att uppdatera en fråga.
För att hålla koll på vilka frågor samt svar som har gjorts av vilken användare så valde jag att ha en colum i varje tabell där jag sparar email, för att smidigt nå vem som har gjort vad. På användar-sidan(Account) skriver jag sedan ut vilka frågor samt svar denna användaren har gjort tack vare att jag kan urskilja med emailen från användaren.

När jag började på "svar"-modulen så började jag göra AnswerController med AnswerformController, i databasen så har jag ett vanligt id för att kunna urskilja alla svar samt en colum med IdQuestion för att urskilja vilka svar som tillhör vilka frågor. I min view answer/list-all.tpl.php så skickar jag med alla svar sedan kollar jag om svaret tillhör frågan med denna if-satsen  if($id == $answer->idQuestion), som du ser skickar jag även med $id från QuestionController som då är id på frågan.

###En fråga kan ha en eller flera taggar kopplade till sig. När man listar en tagg kan man se de frågor som har den taggen. Klicka på en tagg för att komma till de frågor som har taggen kopplat till sig.
Detta var den svåraste delen i hela projektet enligt mig, jag löste detta med att ha en colum i question tabellen som heter tags. För att kunna nu taggarna gjorde jag en TagController, när man skriver en fråga så i fältet Tag så skriver man sina taggar men man urskiljer dem med att skriva ett "," i mellan. Sedan sparar jag hela den strängen rakt in i databasen. Men sedan för att seperera taggarna för att skriva ut dem samt kunna söka på dem så använde jag den inbyggda funktionen "explode()" som då urskiljer strängarna på varje ",". För att inte kunna lägga till många taggar med samma sträng så kollar jag om denna taggen redan finns i tabellen "tag", om den inte finns läggs den till i tabellen men om den redan finns så ökar den poängen (score) för den taggen i tabellen med +1. Sedan för en fråga inte ska kunna ha 2 eller flera av samma tag så gjorde jag om detta sker skickas man bara tillbaka till sidan med alla frågor.

###En fråga kan ha många svar. Varje fråga och svar kan i sin tur ha kommentarer kopplade till sig.
När jag kom hit så hade jag redan fått "svar" delen att funka så att en fråga kan ha många svar, så det ända som behövdes göra här var att fixa kommentarerna. Jag började med en CommentController, en CommentformController samt 2 formulär för ett för att kunna lägga till en kommentar och en för att kunna uppdatera en kommentar. I tabellen för comments så hade jag en vanligt id för att kunna urskilja dem sedan en colum "IdQA" som är id:t till den fråga eller svar som kommentarern tillhör, sedan har jag en colum "type" för att urskilja om detta är en kommentar för fråga eller svar. "type" skickar jag med i formuläret om funktionern kallas från AnswerController så skickar jag med en variabel "$type" som då är "answer" eller om den kallas från QuestionController så är variblen "question".

Senare så skickar jag med all kommentarer i min view comment/list-all.tpl.php och där har jag denna if-satsen if($type==$comment['type'] && $id==$comment['idQA'] ), som du ser skickar jag även med $id som då är id på frågan eller svaret som jag antingen skickar med från QuestionController eller AnswerController. Om detta stämmer så skriver den då ut kommentaren.

###Alla frågor, svar och kommentarer skrivs i Markdown.
Här kör sakapar jag en variabel där jag spara frågan, svaret eller kommentarens innehåll i markdown med doFilter(), såhär ser det tex ut när jag kör det på ett svar $content = $this->textFilter->doFilter($answer->answer,'shortcode, markdown, bbcode'); sen skriver jag ut den variblen.

###Förstasidan skall ge en översikt av senaste frågor tillsammans med de mest populära taggarna och de mest aktiva användarna.

Såhär gör jag in index.php:

$app->dispatcher->forward([
    'controller' =>'question',
    'action'    =>'front'
]);

$app->dispatcher->forward([
    'controller' =>'tag',
    'action'    =>'front'
]);

$app->dispatcher->forward([
    'controller' =>'users',
    'action'    =>'front'
]);

I question frontAction fångar jag upp de 3 senaste frågorna med en funktion som sorterar på created sedan visar resultatet i en view.

Ungerfär samma sak för tag frontAction() fast då fångar jag upp de 3 populäraste taggarna med en funktion som soretera tabellen på "score" sedan har jag tagit en LIMIT på 3 och sedan visas upp i en view.

För att få mest aktiva användarna göra jag samma sak, dvs sorterar på "score" sedan sätter en LIMIT på 3 som sedan visaas upp i en view.

####Webbplatsen skall finnas på GitHub, tillsammans med en README som beskriver hur man checkar ut och installerar sin egen version.
Detta löste jag sist av allt, då jag var helt klar med projektet. För att få igång programmet så får man sätta sin url till längst bak tex "question/setup" för att sätta upp databasen, samma sak får man göra för tag, answer, comment samt users för att få upp hela databasen. Sedan ska det funka som det ska. Har fått mer koll på github nu tack vare föregående kmom samt har användt det i ett projekt i en annan kurs, så det känns bra att bemästra det lite bättre nu.

####Webbplatsen skall finnas i drift med innehåll på studentservern.
Jag la upp på servern för första gågnen när jag var helt klar med kraven, sedan var det lite små fix för att få det att se lite snyggar eut som jag la upp när jag var helt klar.

##Krav 4: Frågor (optionell)

####Ett svar kan märkas ut som ett accepterat svar.
Först gjorde jag detta kravet med att sätta till "score" i tabellen "answer" för att senare fixa så att en besökare kan öka/sänka poängen på ett svar med 1 när hen trycker på en font-awesome icon i form av en tumme upp(för att öka score) samt en tumme ner(för att sänka score). Och så vissas också vissas poängen imellan tummarna. När poäng på ett svar når upp till 10 så markeras det som godkännt med en grön bock, men senare med mer efter tanke så gjorde jag så att användaren som har ställt frågan kan se en länk "Accept Answer" och när personen trycker på den så blir den accepterad och då ändrade jag lite mer i tabellen och la till columen "accepted" och när länken används så blir skickar den in värdet yes i columen och då visas den gröna bocken istället. När den väl är accpterad så visas en annan länk istället " UnAccept answer" som då ändrar värdet till no och då visas inte columen. Anledningen varför jag gjorde såhär var pga att jag tyckte bara den personen som har ställt frågan ska kunna märka svaret som accepterat.

####Varje svar, fråga och kommentar kan röstas på av användare med +1 (up-vote) eller -1 (down-vote), summan av en fråga/svar/kommentars rank är ett betyg på hur “bra” den var.

Eftersom jag gjorde på förra kravet för att acceptera svar först innan jag ändrade det, så där hade jag kravet nästan uppfyllt. Så som funktionen funkar är att den tar med sig id för frågan/svaret/kommentaren för svar och kommentar tar den med idQuestion om det är ett svar eller så tar det med IdQA(som då är id till svaret eller frågan som kommentaren tillhör) om det är en kommentar. Sedan har jag en sista parameter för att kunna ha både up-vote samt down-vote i en funktion som heter $vote.

Så i funktionen använder jag id för att få fram vilken svar/fråga/kommentar som ska uppdateras  och sparar den i en variabel. Sedan if-elseif-sats som kollar om den variablen $vote är "up" isf uppdaterar jag "score" columen med +1 om det inte stämmer kollar den om variablen är "down" isf uppdaterar jag "score" med -1.


####Svaren på en fråga kan sorteras och visas antingen enligt datum, eller rank (antalet röster).
Här la jag till tre länkar "Score", "Newest" och "Oldest", jag fixade om lite bland mina nuvarande funktioner så att i min QuestionController idAction så gör jag en variabel $answers som får sitt värde från en funktion  som har en parameter ($order). I den funktionen kollar jag $order är satt om den är det går den vidare till en annan funktion om sorterar beroende värdet i $order, om värdet inte är satt så får $answers alla värden i tabellen.

####Översikten av frågorna visar hur många svar en fråga har samt vilken rank.
Detta var inte så svårt att fixa då det bara var att skriva ut "score" för frågan, sedan var för att få tag på hur många svar frågan har gjorde jag det med denna enkla foreach-loopen:

foreach ($all as $answer1) {
    if($answer1->idQuestion==$question['id'])
    {
        $temp=$temp+1;
    }
}

sedan skrev jag bara ut $temp variablen på ett passande ställe.


##Krav 5: Användare (optionell)

####Inför ett poängsystem som baseras på användarens aktivitet. Följande ger poäng: Skriva fråga,Skriva svar,Skriva kommentar,Ranken på skriven fråga, svar, kommentar. Summera allt och sätt det till användarens rykte.

Här skapade jag en till colum för användaren "score" som ökas när användaren ställer en fråga, svar eller kommentar.
Användaren får 6 poäng för ett svar 4 för en fråga samt 1 för en kommentar. Sedan vissar en användares poäng på deras "view" sida. Såhär ser det ut när "score" uppdateras för en användaren när hen svarar på en fråga:

$user->save([
    'id'    => $this->Value('userId'),
    'score' => $this->Value('userScore')+6
]);

Detta la jag efter att själva frågan/svaret/kommentaren har skapats.

Jag var tvungen att lägga till 'userScore' i formuläret men jag la det som hidden för det är något som användaren inte ska kunna manipulera, och tack vare det kan jag öka det på detta sättet. Det hade säkert kunnat lösas bättre med en sorts funktion istället men jag ville göra det lätt för mig. Jag når användares "score" eftersom jag skickar med den nuvarande användare i variablen $user till formulären, så här ser det ut:

'userScore' => [
    'type'      =>'hidden',
    'required' => true,
    'validation' =>['not_empty'],
    'value'     => $user->score,
],

Jag gjorde inte så att användaren får mer poäng(bättre rykte) beroende på frågan/svaret/kommentarens poäng, hade nog kunnat fixa det men har andra kurser jag måste lägga mer fokus på. Tänkte ta mig an det i sommar när jag har mer tid.


####Visa en översikt på användarens sida om all aktivitet som användaren gjort, dvs frågor, svar, kommentarer, antalet röstningar gjorda samt vilket rykte användaren har.

Detta kravet har jag inte gjort då jag har haft annat att lägga tid på.

##Krav 6: Valfritt (optionell)

####Förutsatt att du gjort krav 4 och 5 kan du använda detta krav som ett valfritt krav. Beskriv något som du gjort i uppgiften som du anser vara lite extra och berätta hur du löst det. Det kan vara en utseendemässig sak, eller en kodmässig sak. Den bör vara något som du lagt mer än 4-8h på.

Det som jag nog skulle vilja få fram som något "lite extra" hade nog isf varit att när man uppdaterar en användare så märkte jag att det inte gick att nå saker som man skulle ha tillgång till, det var då problemet att jag var tvungen att starta om sessionen med de nya värdena på användaren eftersom sessionen var inte uppdaterad. Sedan märkte jag att om man uppdaterar sin email så föll hela mitt system eftersom det som kopplade ihop användare till frågor, svar samt kommentarer var just email. Detta gjorde jag med tre funktioner som låg i QuestionController, AnswerController och CommentController alla hade samma namn "updateemailAction" där jag då skickar med den nya emailen samt var jag tvungen att lägga till en ny rad i formuläret som jag då kallade "OldEmail" för att bevara den gamla för att sedan kunna använda den för att hitta rätt resultat. I funktionerna så fångar jag upp alla beroende vilken controller jag är i, sedan kollar jag igenom alla om variablen $OldEmail stämmer med det som såt för det objektet i databasen om den gör det sparar jag det med variablen $newEmail. Detta göra jag då för alla 3 controllers.

Sedan gjorde jag så att lösenord fältet är tomt när man uppdaterar eftersom det är inte så snyggt om lösenordet syns, sedan kollar jag om fältet för lösenord är fyllt så uppdaterar den det nya lösenordet för användaren men om det förblir tomt så gör den inte det.

####Skriv ett allmänt stycke om hur projektet gick att genomföra. Problem/lösningar/strul/enkelt/svårt/snabbt/lång tid, etc. Var projektet lätt eller svårt? Tog det lång tid? Vad var svårt och vad gick lätt? Var det ett bra och rimligt projekt för denna kursen?

Detta projektet tyckte jag var roligt då jag kände att jag hade äntligen koll på vad som hände ri ramverket, kände verkligen att jag bemästrade det. Varje gång jag gjorde ett krav så visste jag nästan alltid på ett ungefär hur jag skulle lösa det, visst ibland tog det flera timmar kanske till och med nån dag men jag rörde mig framåt iaf istället för att sitta som en frågetecken som jag har gjort vissa kmom i denna kursen. Även om jag nu kände att jag hade koll på grejer tog det lång tid att få projektet klart, det var mycket små grejer i projektet som tog tid sen var det även ganska många "stora" krav som man behövde fixa men det var en rolig utmanning.

Detta projekt tyckte jag var mycket rimligt till denna kurs, det var många bra krav som jag kände att jag utvecklades på att göra utan någon annan backom ryggen.

####Avsluta med ett sista stycke med dina tankar om kursen och vad du anser om materialet och handledningen (ca 5-10 meningar). Ge feedback till lärarna och förslå eventuella förbättringsförslag till kommande kurstillfällen. Är du nöjd/missnöjd? Kommer du att rekommendera kursen till dina vänner/kollegor? På en skala 1-10, vilket betyg ger du kursen?

Jag tyckte att denna kursen har varit den bästa hittentills när det kommer till webbprogrammering, den har varit ganska komplett täckt mycket. Jag har lärt mig en hel del om hur ramverk funkar, det har varit rimliga kmom även om jag inte tyckte att alla var så roliga.

 Det ända som har varit lite down det har varit att visste jag kan detta ramverket nu och det finns flera tusen där ute
 som funkar på ett annat sätt, så kan jag verkligen ramverk eller kan jag ramverket Anax-MVC?
 Men annars har det varit roligt, jag hoppas att jag får jobba mycket med ramverk i framtiden då jag tycker att det är ganska fräckt.

Jag tycker även att jag fick bra hjälp på labbarna, bra hjälp med folk som var mycket villiga att hjälpa till och lägga ner lite extra tid för att förklara så att man verkligen förstod.

Något som kanske hade varit en liten förbättring på kursen hade kanske varit att gå igenom ramverk lite mer generellt istället för att vissa så mycket hur Anax-MVC funkar.

Jag skulle verkligen kunna tänka mig att rekommendera denna kursen för vänner/kollegor, jag ger denna kursen 9/10
