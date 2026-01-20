Feature: Vooradbeheer (CRUD)
  Naam: Hernan Martino Molina

  1. Feature: Productoverzicht
  Als magazijnmedewerker
  Wil ik een overzicht zien van alle producten
  Zodat ik snel kan zien wat er in voorraad is

  Scenario: Beschikbare producten tonen
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de voorraadpagina
    En: Er zijn producten beschikbaar in het systeem
    Wanneer: De voorraadpagina wordt geladen
    Dan: Zie ik een lijst met beschikbare producten met naam, categorie, EAN en aantal

  Scenario: Geen producten beschikbaar
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de voorraadpagina
    En: Er zijn geen producten beschikbaar in het systeem
    Wanneer: De voorraadpagina wordt geladen
    Dan: Zie ik een melding dat er geen producten beschikbaar zijn


  2. Feature: Product toevoegen
  Als magazijnmedewerker
  Wil ik een nieuw product kunnen toevoegen
  Zodat het product beschikbaar is in het magazijnsysteem

  Scenario: Product succesvol toevoegen
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de voorraadpagina
    dan klik ik op “Product toevoegen”
    Wanneer: Ik vul een unieke productnaam in
    En: Ik kies een categorie
    En: Ik vul een unieke EAN-code van 13 cijfers in
    En: Ik vul een geldig aantal in voorraad in
    En: Ik klik op “Opslaan”
    Dan: Wordt het product opgeslagen
    En: Krijg ik een meding "product aangemaakt"
    En: Zie ik het product terug in het overzicht

  Scenario: Product toevoegen mislukt
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de voorraadpagina
    Wanneer: Ik een product probeer toe te voegen met een productnaam of EAN-code die al bestaat
    Dan: Wordt het product niet opgeslagen
    En: Krijg ik een melding dat de productnaam of EAN-code al bestaat


  3. Feature: Product wijzigen
  Als magazijnmedewerker
  Wil ik een bestaand product kunnen wijzigen
  Zodat foutieve of verouderde gegevens aangepast kunnen worden

  Scenario: Product succesvol wijzigen
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de voorraadpagina
    dan: Ik bevind mij op de pagina van een bestaand product
    Wanneer: Ik wijzig de productnaam naar een unieke naam
    En: Ik pas de categorie en het aantal voorraad aan
    En: Ik klik op “Opslaan”
    Dan: Worden de wijzigingen opgeslagen
    En: Zie ik een bevestigingsmelding "Wijzingen opgestaan"
    En: Zie ik de gewijzigde gegevens in het productoverzicht

  Scenario: Product wijzigen mislukt
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de voorraadpagina
    dan: bevind ik mij op de pagina van een bestaand product
    Wanneer: Ik probeer de productnaam te wijzigen naar een naam die al bestaat bij een ander product
    Of: Ik probeer een EAN code te wijzigen die al aan een ander product is gekoppeld
    Dan: Wordt het product niet opgeslagen
    En: Zie ik een foutmelding dat de "productnaam of EAN code al bestaat:


  4. Feature: Product verwijderen
  Als magazijnmedewerker of directie
  Wil ik een product kunnen verwijderen
  Zodat onnodige of foutieve producten uit het systeem verdwijnen

  Scenario: Product succesvol verwijderen
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de voorraadpagina
    En: Het product is nog nooit gebruikt in een voedselpakket
    Wanneer: Ik het product selecteer en op “Verwijderen” klik
    Dan: Wordt het product verwijderd uit het systeem
    En: Krijg ik een melding “Product verwijderd”
    En: Zie ik het product niet meer terug in het overzicht

  Scenario: Product verwijderen mislukt
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de voorraadpagina
    En: Het product is al gebruikt in een voedselpakket
    Wanneer: Ik probeer het product te verwijderen
    Dan: Wordt het product niet verwijderd
    En: Krijg ik een melding “Product kan niet worden verwijderd, het is al gebruikt in een voedselpakket”


  5. Feature: Categorieoverzicht
  Als directie
  Wil ik een overzicht zien van alle productcategorieën
  Zodat ik kan controleren welke categorieën beschikbaar zijn en eventueel beheren

  Scenario: Beschikbare categorieën tonen
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de Categorieoverzicht
    En: Er zijn categorieën beschikbaar in het systeem
    Wanneer: De categoriepagina wordt geladen
    Dan: Zie ik een lijst van alle categorieën
    En: Kan ik per categorie de naam bekijken

  Scenario: Geen categorieën beschikbaar
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de Categorieoverzicht
    En: Er zijn geen categorieën beschikbaar in het systeem
    Wanneer: De categoriepagina wordt geladen
    Dan: Zie ik een melding dat er "geen categorieën beschikbaar zijn"


  6. Feature: Categorie aanmaken
  Als directie
  Wil ik een nieuwe productcategorie kunnen aanmaken
  Zodat nieuwe producten correct kunnen worden ingedeeld

  Scenario: Categorie succesvol aanmaken
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de Categorieoverzicht
    En: Ik druk op “Categorie aanmaken”
    Wanneer: Ik een unieke categorienaam invoer
    En: Ik klik op “Opslaan”
    Dan: Wordt de categorie opgeslagen
    En: Krijg ik een melding “Categorie aangemaakt”
    En: Zie ik de nieuwe categorie terug in het overzicht

  Scenario: Categorie aanmaken mislukt
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de Categorieoverzicht
    En: Ik druk op “Categorie aanmaken”
    Wanneer: Ik een categorienaam invoer die al bestaat
    Dan: Wordt de categorie niet opgeslagen
    En: Krijg ik een melding “Categorie bestaat al”


  7. Feature: Categorie wijzigen
  Als directie
  Wil ik een bestaande productcategorie kunnen wijzigen
  Zodat categorieën correct en actueel blijven

  Scenario: Categorie succesvol wijzigen
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de Categorieoverzicht
    En: Ik selecteer een bestaande categorie
    Wanneer: Ik de categorienaam aanpas naar een unieke nieuwe naam
    En: Ik klik op “Opslaan”
    Dan: Wordt de wijziging opgeslagen
    En: Krijg ik een melding “Categorie gewijzigd”
    En: Zie ik de gewijzigde categorie terug in het overzicht

  Scenario: Categorie wijzigen niet mogelijk
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de Categorieoverzicht
    dan: selecteer ik een bestaande categorie
    Wanneer: Ik probeer de categorienaam te wijzigen naar een naam die al bestaat
    Dan: Wordt de wijziging niet opgeslagen
    En: Krijg ik een melding “Categorie bestaat al”


  8. Feature: Categorie verwijderen
  Als directie
  Wil ik een productcategorie kunnen verwijderen
  Zodat oude of ongebruikte categorieën uit het systeem kunnen worden gehaald

  Scenario: Categorie succesvol verwijderen
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de Categorieoverzicht
    En: De categorie is niet gekoppeld aan producten
    Wanneer: Ik de categorie selecteer en op “Verwijderen” klik
    Dan: Wordt de categorie verwijderd
    En: Krijg ik een melding “Categorie verwijderd”
    En: Zie ik de categorie niet meer terug in het overzicht

  Scenario: Categorie verwijderen niet mogelijk
    Gegeven: Ik ben op de homepagina
    En ik navigeer naar de Categorieoverzicht
    En: De categorie is gekoppeld aan één of meer producten
    Wanneer: Ik probeer de categorie te verwijderen
    Dan: Wordt de categorie niet verwijderd
    En: Krijg ik een melding “Categorie kan niet worden verwijderd, er zijn producten aan gekoppeld”

