#include "movies.h"
using namespace movies;

/*******************************************************************************************************/
// Struct
/*******************************************************************************************************/
// Mezzo arco, non tiene il nodo sorgente
struct halfEdgeNode{
  VertexNode *vertPtr;
  EdgeType role; // etichetta dell'arco
  halfEdgeNode* next; // puntatore al mezzo arco successivo
};

struct movies::VertexNode{
  Label label;
  NodeType type; // tipo : ACTOR o MOVIE
  halfEdgeNode *adjList;
  VertexNode *next;
};

//const
halfEdgeNode* const emptyHalfEdgeNode = NULL;

/*******************************************************************************************************/
// Funzioni ausiliarie
/*******************************************************************************************************/
// Restituisce true se il grafo e' vuoto, false altrimenti
bool isEmpty(const MovieDB& mdb){
  return (mdb==nullptr);
}

// Ritorna il puntatore al vertice avente label "l" (se esiste)
VertexNode* getVertex(Label l, const MovieDB& mdb) {
  for (movies::MovieDB v = mdb; v != emptyMovieDB; v = v->next){
    if (v->label == l)
      return  v; // trovato, esco
  }
  return emptyMovieDB; // non trovato
}

// Ritorna true se il vertice e' presente
bool isVertexInMovie(Label l, const MovieDB& mdb) {
  return (getVertex(l, mdb)!=emptyMovieDB);
}

// Ritorna true se l'arco e' gia' presente nel grafo
bool isEdgeInGraph(Label from, Label to, const MovieDB& mdb, const EdgeType t){
  VertexNode* vNode = getVertex(from, mdb);
  if (vNode == emptyMovieDB)
    return false;
  for (halfEdgeNode* n = vNode->adjList; n != emptyHalfEdgeNode; n = n->next){
    if (n->vertPtr->label == to && n->role==t)
      return true;
  }
 return false;
}

// Aggiunge il "mezzo edge" alla lista di adiacenza
// Da usare solo se i vertici "from" e "to" sono presenti nel grafo
void addHalfEdge(Label from, Label to, const EdgeType t, MovieDB &mdb) {
  halfEdgeNode *e = new halfEdgeNode;
  e->vertPtr = getVertex(to,mdb);
  e->role = t;
  VertexNode* vNode = getVertex(from, mdb);
  if (vNode->adjList == emptyHalfEdgeNode) {
    vNode->adjList = e;
    e->next = emptyHalfEdgeNode;
  } else {
    e->next = vNode->adjList;
    vNode->adjList = e;
  }
}

//Funzione ausiliaria che stampa lista di adiacenza
void printadj(Label l, const MovieDB& mdb){
  // Variabile ausiliaria che controlla se hanno liste di adiacenza 
  bool found=false;
  VertexNode* vNode = getVertex(l,mdb);
  if(vNode==emptyMovieDB) return;

  cout<< "con lista di adiacenza :" << endl;
  for(halfEdgeNode*n=vNode->adjList; n!=emptyHalfEdgeNode; n=n->next){
    if(vNode->type==PERSON){
      if(n->role == 0){
        found= true;
        cout << "(" << n->vertPtr->label << ", in cui ha recitato)" << " ";
      }
      if(n->role == 1){
        found= true;
        cout << "(" << n->vertPtr->label << ", in cui e' stato direttore)" << " ";
      }
      if(n->role == 2){
        found= true;
        cout << "(" << n->vertPtr->label << ", in cui e' stato produttore)" << " ";
      }
      cout<<endl;
    }
    if(vNode->type==MOVIE){
      if(n->role == 0){
        found= true;
        cout << "(" << n->vertPtr->label << ", attore)" << " ";
      }
      if(n->role == 1){
        found= true;
        cout << "(" << n->vertPtr->label << ", direttore)" << " ";
      }
      if(n->role == 2){
        found= true;
        cout << "(" << n->vertPtr->label << ", produttore)" << " ";
      }
      cout<<endl;
    }
  }
  if(!found){
    cout<< "Non ha liste di adiacenza";
    cout<<endl;
  }
  cout<<endl;
}

// Funzione ausiliaria per creare la lista degli attori che sono stati diretti-prodotti-coattori di un film
void PersonActor(const MovieDB &mdb, Label l, EdgeType t, lista::list & lst){
  VertexNode* prs = getVertex(l,mdb);
  if (prs != nullptr) {
    // Scandisci gli archi in uscita dal nodo persona
    halfEdgeNode* edge = prs->adjList;
    while (edge != emptyHalfEdgeNode) {
      // persona-film
      if (edge->role == t) {
        // Nodo del film corrispondente all'arco
        VertexNode* movieNode = edge->vertPtr;
        // Archi in uscita dal nodo del film
        halfEdgeNode* movieEdge = movieNode->adjList;
        while (movieEdge != emptyHalfEdgeNode) {
          //attore-film
          if (movieEdge->role == ACTED && movieEdge->vertPtr != prs) {
            // Aggiungi l'attore alla lista, senza ripetizioni
            if(!lista::isPresent(movieEdge->vertPtr->label, lst))
              lista::addFront(movieEdge->vertPtr->label,lst);
          }
          movieEdge = movieEdge->next;
        }
      }
      edge = edge->next;
    }
  }
}

//Funzione ausiliaria che mi ritorna la visita alle liste dei coattori
int BaconNumberRecur(const MovieDB &mdb, lista::List &listaAttoriLivello, lista::List &listaAttorVisitati, int &count, bool &isFound){
	// Controllo se lista è vuota
  if (lista::isEmpty(listaAttoriLivello))
  	return NO_BACON_NUMBER;
  count++;

  // nella presente lista si vanno progressivamente ad inserire gli attori legati al livello corrente (il livello e' identificato dalla variabile count)
  // tale lista verra' poi passato come parametro al livello seguente, se Kevin Bacon non e' presente fra gli attori del livello corrente
  lista::List lst = lista::createEmpty();

  for (int i=0; i<listaAttoriLivello.size; ++i){
  	// Metto nella lista "coActorList" tutti gli attori che hanno recitato con l'attore passato nell'occorrenza i-esima della lista 'listaAttoriLivello
    // HO LASCIATO LE STAMPE NEL CASO SI VOLESSE RICORRERE CON CHE PASSI SI E' ARRIVATI AL BACON NUMBER
    //cout << listaAttoriLivello.list[i] <<endl;
    lista::List coActorList = movies::coActors(mdb,listaAttoriLivello.list[i]);
    //cout << coActorList.size <<endl;
    if(lista::isPresent("Kevin Bacon",coActorList)){
      isFound = true;
      return count;
    }
    for (int j=0; j<coActorList.size; ++j){
      if(!(lista::isPresent(coActorList.list[j],listaAttorVisitati))){
      	lista::addFront(coActorList.list[j], lst);
      	lista::addFront(coActorList.list[j], listaAttorVisitati);
  	  }
  	}
  }

  BaconNumberRecur(mdb, lst, listaAttorVisitati, count, isFound);
  if (isFound)
  	return count;
  else
  	return NO_BACON_NUMBER;
}


/*******************************************************************************************************/
// Funzioni da implementare
/*******************************************************************************************************/

// crea un database vuoto
MovieDB movies::createEmpty(){
  return nullptr;
}

// aggiunge un nodo di tipo PERSON o MOVIE con nome l, fallisce se un nodo con quell'etichetta è già presente
// restituisce true se l'inserimento ha avuto successo
bool movies::addVertex(MovieDB &mdb, const Label l, const NodeType t){
  //fallisce se un nodo con quell'etichetta è già presente
  if (isVertexInMovie(l, mdb))
    return false;
  MovieDB node = new VertexNode;
  node->label = l;
  node->type = t;
  node->adjList = emptyHalfEdgeNode;
  if (isEmpty(mdb)) {
    mdb = node;
    node->next = emptyMovieDB;
  }
  else{
    node->next = mdb;
    mdb = node;
  }
  return true;
}

// aggiunge un arco tra `person` e `movie` con tipo t (ACTED, DIRECTED o PRODUCED)
// fallisce se i nodi non hanno tipo appropriato o se un arco identico (stessa persona, film e tipo) esiste già
bool movies::addEdge(MovieDB &mdb, const Label from, const Label to, const EdgeType t){
  // non permetto arco tra un nodo ed esso stesso
  if (from == to)
    return false;
  // entrambi i nodi devono gia' esistere nel grafo
  if (!isVertexInMovie(from, mdb) || !isVertexInMovie(to, mdb))
    return false;
  // tra i due nodi non deve gia' esserci un arco
  if (isEdgeInGraph(from,to,mdb, t) || isEdgeInGraph(to,from,mdb, t))
    return false;
  // tutmovie ok, procediamo
  addHalfEdge(from, to, t, mdb);
  addHalfEdge(to, from, t, mdb);
  return true;
}

// numero di nodi con tipo t
int movies::numNodesPerType(const MovieDB &mdb, NodeType t){
  int num = 0;
  // Iterazione per incrementare num e contare i nodi
  for (MovieDB v = mdb; v != emptyMovieDB; v = v->next) {
    if (v->type == t)
    num++;
  }
  return num;
}

// numero di archi con tipo t
int movies::numEdgesPerType(const MovieDB &mdb, EdgeType t){
  int num = 0;
  // Iterazione per incrementare num e contare gli archi
  for (MovieDB v = mdb; v != emptyMovieDB; v = v->next) {
    halfEdgeNode* edge = v->adjList;
    while (edge != nullptr) {
      if (edge->role == t)
        num++;
      edge = edge->next;
    }
  }
  return num/2;
}

// lista, senza ripetizioni, di attori che hanno interpretato un film con l'attore con etichetta l
// restituisce una lista vuota se l non è una persona o non appartiene al grafo
lista::List movies::coActors(const MovieDB &mdb, Label l){
  lista::List lst = lista::createEmpty();
  PersonActor(mdb,l, ACTED, lst);
  return lst;
}

// lista, senza ripetizioni, di attori che hanno interpretato un film prodotto da l
// restituisce una lista vuota se l non è una persona o non appartiene al grafo
lista::List movies::actorsProducedBy(const MovieDB &mdb, Label l){
  lista::List lst = lista::createEmpty();
  PersonActor(mdb,l, PRODUCED, lst);
  return lst;
}

// lista, senza ripetizioni, di attori che hanno interpretato un film diretto da l
// restituisce una lista vuota se l non è una persona o non appartiene al grafo
lista::List movies::actorsDirectedBy(const MovieDB &mdb, Label l){
    // Creo la lista in cui andranno gli attori che hanno interpretato un film diretto da l
  lista::List lst = lista::createEmpty();
  PersonActor(mdb,l, DIRECTED, lst);
  return lst;
}

// numero di Bacon di L; restituisce NO BACON NUMBER se L non A una persona o non ha un numero di Bacon
//Il dove KB stesso ha numero di Bacon e, un attore che ha recitato con KB ha come numero di Bacon 1 e in generale un attore X ha come numero di Bacon 1
int movies::BaconNumber(const MovieDB &mdb, Label l){
  // Tolgo i nodi gia' visitati
  int count=0;
  // Caso in cui label e' Kevin Bacon
  if (l=="Kevin Bacon")
  	return count;
  // Chiamata per ottenere l'attore
  VertexNode* actorNode = getVertex(l,mdb);
  // Controlli se L non A una persona o non ha un numero di Bacon
  if(actorNode==emptyMovieDB || actorNode->type != PERSON)
    return NO_BACON_NUMBER;

  bool isFound = false;

  // nella presente lista si vanno ad inserire tutti gli attori del livello corrente
  // livello 0: la lista e' composta da un solo elemento: il parametro 'l' della funzione BaconNumber
  // livello 1: tutti gli attori che hanno lavorato con gli attori del livello 0 (un solo attore in questo caso)
  // livello 2: tutti gli attori che hanno lavorato con gli attori del livello 1
  // .. in generale ..
  // livello n: tutti gli attori che hanno lavorato con gli attori del livello n-1
  lista::List listaAttoriLivello = lista::createEmpty();

  // nella presente lista si vanno ad inserire tutti gli attori progressivamente 'visitati' nei vari livelli
  lista::List listaAttorVisitati = lista::createEmpty();

  addFront(l, listaAttoriLivello);
  BaconNumberRecur(mdb, listaAttoriLivello, listaAttorVisitati, count, isFound);

  if (isFound)
  	return count;
  else
    return NO_BACON_NUMBER;
}

//Il formato che ho scelto e' un formato molto base in cui stampo prima le persone e poi i film
// successivamente stampo le liste di collisione delle persone e dei film
void printDB(const movies::MovieDB &mdb){
  cout<< "Le persone sono :" << endl;
  for (movies::MovieDB mv=mdb; mv!=emptyMovieDB; mv=mv->next)
  {
    if (mv->type == PERSON){
      cout<< mv->label << endl;
      printadj(mv->label, mdb);
    }
  }
  cout<< "I film sono :" << endl;
  for (movies::MovieDB mv=mdb; mv!=emptyMovieDB; mv=mv->next)
  {
    if(mv->type == MOVIE){
      cout<< mv->label << endl;
      printadj(mv->label, mdb);
      }
  }
}
