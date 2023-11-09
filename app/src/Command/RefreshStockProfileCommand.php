<?php

namespace App\Command;

use App\Entity\Stock;
use App\Http\FinanceApiClientInterface;
use App\Http\financeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:refresh-stock-profile',
    description: 'Refresh a stock profile from the Yahoo Finance API. Update the record in the DB',
)]
class RefreshStockProfileCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FinanceApiClientInterface $financeApiClient,
        private SerializerInterface $serializer,
        private LoggerInterface $logger
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('symbol', InputArgument::REQUIRED, 'Stock symbol e.g. INTC for Intel')
            ->addArgument('region', InputArgument::OPTIONAL, 'The region of the company e.g. US for United States', 'US')
            ->addArgument('lang', InputArgument::OPTIONAL, 'The lang e.g. en-US', 'en-US')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $symbol = $input->getArgument('symbol');
            $region = $input->getArgument('region');
            $lang = $input->getArgument('lang');


            // 1. Ping Yahoo API and grab the response (a stock profile)
            /**
             *  [
             *      'statusCode' => $statusCode,
             *      'content' => $someJsonContent,
             *  ]
             */
            $stockProfile = $this->financeApiClient->fetchStockProfile($symbol, $region, $lang);


            if ($stockProfile->getStatusCode() !== 200) {

                $output->writeln($stockProfile->getContent());

                return Command::FAILURE;
            }

            // 2.b. Use response to create a record if it doesn't exist

            // Attempt to find a record in the DB using the $stockPrice symbol
            $symbol = json_decode($stockProfile->getContent())->symbol ?? null;

            if ($stock = $this->entityManager->getRepository(Stock::class)->findOneBy(['symbol' => $symbol])) {
                // update if found

                $this->serializer->deserialize($stockProfile->getContent(), Stock::class, 'json', [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $stock
                ]);

            } else {
                // Create a new stock record if not found

                $stock = $this->serializer->deserialize($stockProfile->getContent(), Stock::class, 'json');
    //        $stock->setPrice((float) $stock->getPrice());
    //        dd($stock);

    //        $stock = new Stock();
    //        $stock->setSymbol($stockProfile->symbol);
    //        $stock->setShortName($stockProfile->shortName);
    //        $stock->setCurrency($stockProfile->currency);
    //        $stock->setExchangeName($stockProfile->exchangeName);
    //        $stock->setRegion($stockProfile->region);
    //        $stock->setLang($stockProfile->lang);
    //        $stock->setPrice($stockProfile->price);
    //        $stock->setPreviousClose($stockProfile->previousClose);
    //        $priceChange = $stockProfile->price - $stockProfile->previousClose;
    //        $stock->setPriceChange($priceChange);
            }

            $this->entityManager->persist($stock);

            $this->entityManager->flush();

            $output->writeln($stock->getShortName() . ' has been saved / updated.');

            return Command::SUCCESS;

        } catch (\Exception $exception) {

            // Log everything and learn
            $this->logger->warning(get_class($exception) . ': ' . $exception->getMessage() . ' in ' . $exception->getFile()
                . ' on line ' . $exception->getLine() . ' using [symbol/region] ' . '[' . $input->getArgument('symbol') .
                '/' . $input->getArgument('region') . ']');

            return Command::FAILURE;
        }
    }
}
