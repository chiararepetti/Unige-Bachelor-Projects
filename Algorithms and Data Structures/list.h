// Implementa LIST con strutture indicizzate e tipo base Elem dotato di ordinamento
#include <cstddef>
#include <iostream>
#include <stdexcept>
#include <vector>
#include <fstream>
#include <string>

using namespace std;

//Utilizzando mac chiamo lista invece di list
namespace lista{

/**************************** Tipi e costanti *********************************************/
const int BLOCKDIM = 100;
typedef string Elem;      // tipo base, compatibile con il tipo Label dell'albero

const Elem emptyElem = "$#$#$";
struct list {
    Elem* list;
    int size;
    int maxsize;
};

typedef list List;


/************************* operazioni ******************************************/

void clear(List&);          /* "smantella" la lista */
void set(int, Elem, List&); /* modifica l'elemento in posizione pos */
void add(int, Elem, List&); /* inserisce l'elemento in posizione pos, shiftando a destra gli altri elementi */
void addBack(Elem, List&);  /* inserisce l'elemento alla fine della lista */
void addFront(Elem, List&); /* inserisce l'elemento all'inizio della lista */
void removePos(int, List&); /* cancella l'elemento in posizione pos dalla lista  */
Elem get(int, const List&);	 /* restituisce l'elemento in posizione pos */
List createEmpty();          /* crea la lista vuota e la restituisce */
bool isEmpty(const List&);
int size(const List&);
bool isPresent(Elem, const List&);  /* Funzione che ritorna vera se l'elemento e' gia' presente nella lista */
}

void printList(const lista::List&);
