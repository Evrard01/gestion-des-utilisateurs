<?php

namespace App\Http\Livewire\Users;

use alert;
use Throwable;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserCrud extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nom = "", $prenom = "", $email = "";
    public $user;
    public $updateUser = false;
    public $search = "";
    public $searchMode = false;
    public $resultats;

    public function store()
    {
        $this->validate([
            "nom" => "required|string|max:10",
            "prenom" => "required|string|max:10",
            "email" => "required|email|unique:users,email",
        ]);

        try {
            User::create([
                "nom" => $this->nom,
                "prenom" => $this->prenom,
                "email" => $this->email,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]);
            alert()->success('Reussite', 'Votre utilisateur a été ajouter avec succes');
            return redirect()->route('home');
            $this->reset(["nom", "prenom", "email"]);
            $this->resetErrorBag(["nom", "prenom", "email"]);
            $this->updateUser = false;
        } catch (Throwable $e) {
            alert()->error('Echec', 'Quelques chose c\'est mal passée, veuillez reassayer');
            return redirect()->route('home');
        }
    }

    public function resetFilters()
    {
        $this->reset(["nom", "prenom", "email"]);
        $this->resetErrorBag(["nom", "prenom", "email"]);
        $this->updateUser = false;
    }


    public function edit(User $user)
    {
        try {
            $this->user = $user;
            $this->nom = $user->nom;
            $this->prenom = $user->prenom;
            $this->email = $user->email;
            $this->updateUser = true;
        } catch (Throwable $e) {
            alert()->error('Echec', 'Quelques chose c\'est mal passée, veuillez reassayer l\'edition');
            return redirect()->route('home');
        }
    }

    public function update()
    {
        $this->validate([
            "email" => "required|email|unique:users,email," . $this->user->id,
            "nom" => "required|string|max:10|unique:users,nom," . $this->user->id,
            "prenom" => "required|string|max:10|unique:users,prenom," . $this->user->id,
        ]);

        try {
            $user = $this->user;
            $user->nom = $this->nom;
            $user->prenom = $this->prenom;
            $user->email = $this->email;
            $user->save();
            alert()->success('Reussite', 'Votre utilisateur a été modifié avec succes');
            return redirect()->route('home');
            $this->reset(["nom", "prenom", "email"]);
            $this->resetErrorBag(["nom", "prenom", "email"]);
            $this->updateUser = false;
        } catch (Throwable $e) {
            alert()->error('Echec', 'Quelques chose c\'est mal passée, veuillez reassayer la modification');
            return redirect()->route('home');
        }
    }

    public function delete(User $user){
        try{
            $user->delete();
            alert()->success('Reussite', 'Votre utilisateur a été supprimé avec succes');
            return redirect()->route('home');
        }catch(Throwable $e){
            alert()->error('Echec', 'Quelques chose c\'est mal passée, veuillez reassayer la modification');
            return redirect()->route('home');
        }
    }

    public function reserche(){
        $this->validate([
            "search"=>"required",
        ]);

        try{
            $this->resultats = User::where('nom','like','%'.$this->search.'%')->get();
            if(count($this->resultats)>0){
                $this->searchMode = true;
            }else{
                $this->searchMode = false;
                alert()->warning('', "Aucun n'utilisateur pour cette recherche");
                return redirect()->route('home');
            }
        }catch(Throwable $e){
            alert()->error('Echec', 'Quelques chose c\'est mal passée, veuillez reassayer la modification');
            return redirect()->route('home');
        }
    }

    public function searchover(){
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.users.user-crud', [
            "users" => User::paginate(8)
        ]);
    }
}
