<?php 

require_once ('../DBManager.php');
require ('../competition/CompetitionClass.php');

class ManagerCompetition extends DBManager {

    public function getAllCompetitions() {
        $res = $this->getConnexion()->query('SELECT * from competition Order by competition.id ASC');

        $competitions = [];

        foreach($res as $row) {
          $newCompetition = new Competition();
          $newCompetition->setId($row['id']);
          $newCompetition->setName($row['name']);
          $newCompetition->setDescription($row['description']);
          $newCompetition->setCity($row['city']);
          $newCompetition->setFormat($row['format']);
          $newCompetition->setCashPrize($row['cash_prize']);

          $competitions[] = $newCompetition;
        }
        return $competitions;
    }
    public function create($competition) {
    $request = 'INSERT INTO competition (name, description, city, format, cash_prize) VALUE (?, ?, ?, ?, ?)';
    $query = $this->getConnexion()->prepare($request);

    $query->execute([
        $competition->getName(), $competition->getDescription(), $competition->getCity(), $competition->getFormat(), $competition->getCashPrize()
    ]);

    // Rafraichie la page
    header('Refresh:0');
  }

  public function findById($competitionId) {
  $request = 'SELECT * FROM competition WHERE id = :id';
  $query = $this->getConnexion()->prepare($request);
  $query->execute([':id' => $competitionId]);
  $row = $query->fetch();

  if ($row) {
      $newCompetition = new Competition();
      $newCompetition->setId($row['id']);
      $newCompetition->setName($row['name']);
      $newCompetition->setDescription($row['description']);
      $newCompetition->setCity($row['city']);
      $newCompetition->setFormat($row['format']);
      $newCompetition->setCashPrize($row['cash_prize']);
      return $newCompetition;
  }

  return null;
}

  public function delete($competitionId) {
     $competitionToDelete = $this->findById($competitionId);
     if($competitionToDelete) {
       $request = 'DELETE from competition WHERE id = ' . $competitionId;
       $query = $this->getConnexion()->prepare($request);
       $query->execute();

       header('Location:admin_competition.php');
       exit();
     }
  }

  public function edit( $competitionId, $name, $description, $city, $format, $cash_prize)
  {
      $request = 'UPDATE competition SET name = ?, description = ?, city = ?, format = ?, cash_prize = ? WHERE id = ?';
      $query = $this->getConnexion()->prepare($request);
      $query->execute([$name, $description, $city, $format, $cash_prize, $competitionId]);
      header('Location:admin_competition.php');
      exit();
  }

}

?>