<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Store;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class StoreTest extends TestCase
{
    public function testTheFirstStoreAddress(): void
    {
        $storeAddress = Store::with('address')
            ->with('address.city')
            ->with('address.city.country')
            ->first();

        Log::info('testTheFirstStoreAddress', [$storeAddress]);

        /*
        [2021-11-27 16:03:09] testing.INFO: testTheFirstStoreAddress [
        {"App\\Models\\Store":{"id":1,"manager_staff_id":1,"address_id":1,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
        "address":{"id":1,"address":"47 MySakila Drive","address2":null,"district":"Alberta","city_id":300,"postal_code":"","phone":"","location":"0x0000000001010000003E0A325D63345CC0761FDB8D99D94840","created_at":"2014-09-25T22:30:27.000000Z","updated_at":"2014-09-25T22:30:27.000000Z",
        "city":{"id":300,"city":"Lethbridge","country_id":20,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":20,"country":"Canada","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}
        }}}}]
        */

        $this->assertSame(1, $storeAddress->address->id);
        $this->assertSame('47 MySakila Drive', $storeAddress->address->address);
        $this->assertSame('Alberta', $storeAddress->address->district);
        $this->assertSame('Lethbridge', $storeAddress->address->city->city);
        $this->assertSame('Canada', $storeAddress->address->city->country->country);
    }

    public function testTheSecondStoreAddress(): void
    {
        $storeAddress = Store::with('address')
            ->with('address.city')
            ->with('address.city.country')
            ->find(2);

        Log::info('testTheSecondStoreAddress', [$storeAddress]);

        /*
        [2021-11-27 15:58:13] testing.INFO: testTheSecondStoreAddress
        [{"App\\Models\\Store":
        {"id":2,"manager_staff_id":2,"address_id":2,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
        "address":{"id":2,"address":"28 MySQL Boulevard","address2":null,"district":"QLD","city_id":576,"postal_code":"","phone":"","location":"0x0000000001010000008E10D4DF812463404EE08C5022A23BC0","created_at":"2014-09-25T22:30:09.000000Z","updated_at":"2014-09-25T22:30:09.000000Z",
        "city":{"id":576,"city":"Woodridge","country_id":8,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":8,"country":"Australia","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}
        }}}}]
        */
        $this->assertSame('28 MySQL Boulevard', $storeAddress->address->address);
        $this->assertSame('QLD', $storeAddress->address->district);
        $this->assertSame('Woodridge', $storeAddress->address->city->city);
        $this->assertSame('Australia', $storeAddress->address->city->country->country);
    }

    public function testTheFirstStoreHasManagerStaffOne(): void
    {
        $storeManager = Store::with('staff')
            ->first();

        Log::info('testTheFirstStoreHasManagerStaffOne', [$storeManager]);

        /*
            [2021-11-27 19:14:55] testing.INFO: testTheFirstStoreHasManagerStaffOne
        [{"App\\Models\\Store":{"id":1,"manager_staff_id":1,"address_id":1,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
        "staff":{"id":1,"first_name":"Mike","last_name":"Hillyer","address_id":3,"picture":null,"email":"Mike.Hillyer@sakilastaff.com","store_id":1,"active":1,"username":"Mike","password":"8cb2237d0679ca88db6464eac60da96345513964","created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z"}
        }}]
        */

        $this->assertSame(1, $storeManager->staff->id);
        $this->assertSame('Mike', $storeManager->staff->first_name);
    }

    public function testTheSecondStoreHasManagerStaffTwo(): void
    {
        $storeManager = Store::with('staff')
            ->find(2);

        Log::info('testTheSecondStoreHasManagerStaffTwo', [$storeManager]);

        /*
            [2021-11-27 19:16:41] testing.INFO: testTheSecondStoreHasManagerStaffTwo
        [{"App\\Models\\Store":{"id":2,"manager_staff_id":2,"address_id":2,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
        "staff":{"id":2,"first_name":"Jon","last_name":"Stephens","address_id":4,"picture":null,"email":"Jon.Stephens@sakilastaff.com","store_id":2,"active":1,"username":"Jon","password":null,"created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z"}
        }}]
        */

        $this->assertSame(2, $storeManager->staff->id);
        $this->assertSame('Jon', $storeManager->staff->first_name);
    }

    public function testTheFirstStoreHas326Customers(): void
    {
        $storeCustomerCount = Store::withCount('customers')->first();

        $this->assertSame(326, $storeCustomerCount->customers_count);
    }

    public function testTheSecondStoreHas326Customers(): void
    {
        $storeCustomerCount = Store::withCount('customers')->find(2);

        $this->assertSame(273, $storeCustomerCount->customers_count);
    }

    public function testTheFirstStoreFirstCustomerIsMarySmith(): void
    {
        $store = Store::first();

        $firstCustomer = $store->customers->first();
        $this->assertSame('MARY', $firstCustomer->first_name);
        $this->assertSame('SMITH', $firstCustomer->last_name);
    }

    public function testActiveCustomersCountByStore(): void
    {
        $stores = Store::with(['customers:id,active,store_id'])->get();

        Log::info('testActiveCustomersCountByStore', [$stores->find(1)->customers]);

        /*
        [2021-11-30 22:55:36] testing.INFO: testActiveCustomersCountByStore [
        {"Illuminate\\Database\\Eloquent\\Collection":[
        {"id":1,"active":1,"store_id":1},
        {"id":2,"active":1,"store_id":1},
        {"id":3,"active":1,"store_id":1},
        {"id":5,"active":1,"store_id":1},
        {"id":7,"active":1,"store_id":1},
        {"id":10,"active":1,"store_id":1},
        {"id":12,"active":1,"store_id":1},
        {"id":15,"active":1,"store_id":1},
        {"id":17,"active":1,"store_id":1},
        {"id":19,"active":1,"store_id":1},
        {"id":21,"active":1,"store_id":1},
        {"id":22,"active":1,"store_id":1},
        {"id":25,"active":1,"store_id":1},
        {"id":28,"active":1,"store_id":1},
        {"id":30,"active":1,"store_id":1},
        {"id":32,"active":1,"store_id":1},
        {"id":37,"active":1,"store_id":1},
        {"id":38,"active":1,"store_id":1},
        {"id":39,"active":1,"store_id":1},
        {"id":41,"active":1,"store_id":1},
        {"id":44,"active":1,"store_id":1},
        {"id":45,"active":1,"store_id":1},
        {"id":47,"active":1,"store_id":1},
        {"id":48,"active":1,"store_id":1},
        {"id":50,"active":1,"store_id":1},
        {"id":51,"active":1,"store_id":1},
        {"id":52,"active":1,"store_id":1},
        {"id":53,"active":1,"store_id":1},
        {"id":54,"active":1,"store_id":1},
        {"id":56,"active":1,"store_id":1},
        {"id":58,"active":1,"store_id":1},
        {"id":59,"active":1,"store_id":1},
        {"id":60,"active":1,"store_id":1},
        {"id":62,"active":1,"store_id":1},
        {"id":63,"active":1,"store_id":1},
        {"id":67,"active":1,"store_id":1},
        {"id":68,"active":1,"store_id":1},
        {"id":71,"active":1,"store_id":1},
        {"id":74,"active":1,"store_id":1},
        {"id":78,"active":1,"store_id":1},
        {"id":79,"active":1,"store_id":1},
        {"id":80,"active":1,"store_id":1},
        {"id":81,"active":1,"store_id":1},
        {"id":82,"active":1,"store_id":1},
        {"id":83,"active":1,"store_id":1},
        {"id":87,"active":1,"store_id":1},
        {"id":89,"active":1,"store_id":1},
        {"id":93,"active":1,"store_id":1},
        {"id":94,"active":1,"store_id":1},
        {"id":96,"active":1,"store_id":1},
        {"id":98,"active":1,"store_id":1},
        {"id":100,"active":1,"store_id":1},
        {"id":101,"active":1,"store_id":1},
        {"id":102,"active":1,"store_id":1},
        {"id":103,"active":1,"store_id":1},
        {"id":104,"active":1,"store_id":1},
        {"id":105,"active":1,"store_id":1},
        {"id":106,"active":1,"store_id":1},
        {"id":107,"active":1,"store_id":1},
        {"id":108,"active":1,"store_id":1},
        {"id":111,"active":1,"store_id":1},
        {"id":115,"active":1,"store_id":1},
        {"id":116,"active":1,"store_id":1},
        {"id":117,"active":1,"store_id":1},
        {"id":118,"active":1,"store_id":1},
        {"id":119,"active":1,"store_id":1},
        {"id":121,"active":1,"store_id":1},
        {"id":122,"active":1,"store_id":1},
        {"id":124,"active":0,"store_id":1},
        {"id":125,"active":1,"store_id":1},
        {"id":126,"active":1,"store_id":1},
        {"id":128,"active":1,"store_id":1},
        {"id":129,"active":1,"store_id":1},
        {"id":130,"active":1,"store_id":1},
        {"id":133,"active":1,"store_id":1},
        {"id":134,"active":1,"store_id":1},
        {"id":138,"active":1,"store_id":1},
        {"id":139,"active":1,"store_id":1},
        {"id":140,"active":1,"store_id":1},
        {"id":141,"active":1,"store_id":1},
        {"id":142,"active":1,"store_id":1},
        {"id":143,"active":1,"store_id":1},
        {"id":144,"active":1,"store_id":1},
        {"id":145,"active":1,"store_id":1},
        {"id":146,"active":1,"store_id":1},
        {"id":148,"active":1,"store_id":1},
        {"id":149,"active":1,"store_id":1},
        {"id":152,"active":1,"store_id":1},
        {"id":155,"active":1,"store_id":1},
        {"id":156,"active":1,"store_id":1},
        {"id":158,"active":1,"store_id":1},
        {"id":159,"active":1,"store_id":1},
        {"id":161,"active":1,"store_id":1},
        {"id":163,"active":1,"store_id":1},
        {"id":166,"active":1,"store_id":1},
        {"id":168,"active":1,"store_id":1},
        {"id":170,"active":1,"store_id":1},
        {"id":172,"active":1,"store_id":1},
        {"id":173,"active":1,"store_id":1},
        {"id":175,"active":1,"store_id":1},
        {"id":176,"active":1,"store_id":1},
        {"id":179,"active":1,"store_id":1},
        {"id":182,"active":1,"store_id":1},
        {"id":184,"active":1,"store_id":1},
        {"id":185,"active":1,"store_id":1},
        {"id":188,"active":1,"store_id":1},
        {"id":189,"active":1,"store_id":1},
        {"id":191,"active":1,"store_id":1},
        {"id":192,"active":1,"store_id":1},
        {"id":195,"active":1,"store_id":1},
        {"id":196,"active":1,"store_id":1},
        {"id":201,"active":1,"store_id":1},
        {"id":203,"active":1,"store_id":1},
        {"id":204,"active":1,"store_id":1},
        {"id":206,"active":1,"store_id":1},
        {"id":207,"active":1,"store_id":1},
        {"id":208,"active":1,"store_id":1},
        {"id":211,"active":1,"store_id":1},
        {"id":213,"active":1,"store_id":1},
        {"id":214,"active":1,"store_id":1},
        {"id":216,"active":1,"store_id":1},
        {"id":218,"active":1,"store_id":1},
        {"id":221,"active":1,"store_id":1},
        {"id":223,"active":1,"store_id":1},
        {"id":225,"active":1,"store_id":1},
        {"id":227,"active":1,"store_id":1},
        {"id":229,"active":1,"store_id":1},
        {"id":231,"active":1,"store_id":1},
        {"id":234,"active":1,"store_id":1},
        {"id":235,"active":1,"store_id":1},
        {"id":236,"active":1,"store_id":1},
        {"id":237,"active":1,"store_id":1},
        {"id":238,"active":1,"store_id":1},
        {"id":240,"active":1,"store_id":1},
        {"id":242,"active":1,"store_id":1},
        {"id":243,"active":1,"store_id":1},
        {"id":245,"active":1,"store_id":1},
        {"id":246,"active":1,"store_id":1},
        {"id":247,"active":1,"store_id":1},
        {"id":248,"active":1,"store_id":1},
        {"id":253,"active":1,"store_id":1},
        {"id":258,"active":1,"store_id":1},
        {"id":260,"active":1,"store_id":1},
        {"id":261,"active":1,"store_id":1},
        {"id":263,"active":1,"store_id":1},
        {"id":264,"active":1,"store_id":1},
        {"id":267,"active":1,"store_id":1},
        {"id":268,"active":1,"store_id":1},
        {"id":269,"active":1,"store_id":1},
        {"id":270,"active":1,"store_id":1},
        {"id":271,"active":0,"store_id":1},
        {"id":272,"active":1,"store_id":1},
        {"id":274,"active":1,"store_id":1},
        {"id":276,"active":1,"store_id":1},
        {"id":283,"active":1,"store_id":1},
        {"id":284,"active":1,"store_id":1},
        {"id":285,"active":1,"store_id":1},
        {"id":286,"active":1,"store_id":1},
        {"id":288,"active":1,"store_id":1},
        {"id":289,"active":1,"store_id":1},
        {"id":290,"active":1,"store_id":1},
        {"id":291,"active":1,"store_id":1},
        {"id":295,"active":1,"store_id":1},
        {"id":297,"active":1,"store_id":1},
        {"id":298,"active":1,"store_id":1},
        {"id":300,"active":1,"store_id":1},
        {"id":302,"active":1,"store_id":1},
        {"id":305,"active":1,"store_id":1},
        {"id":306,"active":1,"store_id":1},
        {"id":308,"active":1,"store_id":1},
        {"id":309,"active":1,"store_id":1},
        {"id":314,"active":1,"store_id":1},
        {"id":316,"active":1,"store_id":1},
        {"id":318,"active":1,"store_id":1},
        {"id":321,"active":1,"store_id":1},
        {"id":322,"active":1,"store_id":1},
        {"id":325,"active":1,"store_id":1},
        {"id":326,"active":1,"store_id":1},
        {"id":330,"active":1,"store_id":1},
        {"id":331,"active":1,"store_id":1},
        {"id":332,"active":1,"store_id":1},
        {"id":335,"active":1,"store_id":1},
        {"id":336,"active":1,"store_id":1},
        {"id":337,"active":1,"store_id":1},
        {"id":338,"active":1,"store_id":1},
        {"id":340,"active":1,"store_id":1},
        {"id":341,"active":1,"store_id":1},
        {"id":342,"active":1,"store_id":1},
        {"id":343,"active":1,"store_id":1},
        {"id":344,"active":1,"store_id":1},
        {"id":345,"active":1,"store_id":1},
        {"id":346,"active":1,"store_id":1},
        {"id":350,"active":1,"store_id":1},
        {"id":351,"active":1,"store_id":1},
        {"id":352,"active":1,"store_id":1},
        {"id":353,"active":1,"store_id":1},
        {"id":357,"active":1,"store_id":1},
        {"id":362,"active":1,"store_id":1},
        {"id":364,"active":1,"store_id":1},
        {"id":366,"active":1,"store_id":1},
        {"id":367,"active":1,"store_id":1},
        {"id":368,"active":0,"store_id":1},
        {"id":371,"active":1,"store_id":1},
        {"id":373,"active":1,"store_id":1},
        {"id":376,"active":1,"store_id":1},
        {"id":377,"active":1,"store_id":1},
        {"id":378,"active":1,"store_id":1},
        {"id":379,"active":1,"store_id":1},
        {"id":380,"active":1,"store_id":1},
        {"id":383,"active":1,"store_id":1},
        {"id":385,"active":1,"store_id":1},
        {"id":386,"active":1,"store_id":1},
        {"id":389,"active":1,"store_id":1},
        {"id":390,"active":1,"store_id":1},
        {"id":391,"active":1,"store_id":1},
        {"id":393,"active":1,"store_id":1},
        {"id":396,"active":1,"store_id":1},
        {"id":397,"active":1,"store_id":1},
        {"id":398,"active":1,"store_id":1},
        {"id":399,"active":1,"store_id":1},
        {"id":402,"active":1,"store_id":1},
        {"id":403,"active":1,"store_id":1},
        {"id":405,"active":1,"store_id":1},
        {"id":406,"active":0,"store_id":1},
        {"id":407,"active":1,"store_id":1},
        {"id":408,"active":1,"store_id":1},
        {"id":411,"active":1,"store_id":1},
        {"id":414,"active":1,"store_id":1},
        {"id":415,"active":1,"store_id":1},
        {"id":417,"active":1,"store_id":1},
        {"id":419,"active":1,"store_id":1},
        {"id":420,"active":1,"store_id":1},
        {"id":421,"active":1,"store_id":1},
        {"id":422,"active":1,"store_id":1},
        {"id":426,"active":1,"store_id":1},
        {"id":430,"active":1,"store_id":1},
        {"id":432,"active":1,"store_id":1},
        {"id":433,"active":1,"store_id":1},
        {"id":434,"active":1,"store_id":1},
        {"id":436,"active":1,"store_id":1},
        {"id":438,"active":1,"store_id":1},
        {"id":440,"active":1,"store_id":1},
        {"id":441,"active":1,"store_id":1},
        {"id":442,"active":1,"store_id":1},
        {"id":445,"active":1,"store_id":1},
        {"id":447,"active":1,"store_id":1},
        {"id":448,"active":1,"store_id":1},
        {"id":450,"active":1,"store_id":1},
        {"id":451,"active":1,"store_id":1},
        {"id":452,"active":1,"store_id":1},
        {"id":453,"active":1,"store_id":1},
        {"id":458,"active":1,"store_id":1},
        {"id":459,"active":1,"store_id":1},
        {"id":460,"active":1,"store_id":1},
        {"id":461,"active":1,"store_id":1},
        {"id":464,"active":1,"store_id":1},
        {"id":465,"active":1,"store_id":1},
        {"id":466,"active":1,"store_id":1},
        {"id":468,"active":1,"store_id":1},
        {"id":470,"active":1,"store_id":1},
        {"id":471,"active":1,"store_id":1},
        {"id":472,"active":1,"store_id":1},
        {"id":476,"active":1,"store_id":1},
        {"id":477,"active":1,"store_id":1},
        {"id":478,"active":1,"store_id":1},
        {"id":479,"active":1,"store_id":1},
        {"id":480,"active":1,"store_id":1},
        {"id":481,"active":1,"store_id":1},
        {"id":482,"active":0,"store_id":1},
        {"id":484,"active":1,"store_id":1},
        {"id":485,"active":1,"store_id":1},
        {"id":486,"active":1,"store_id":1},
        {"id":489,"active":1,"store_id":1},
        {"id":490,"active":1,"store_id":1},
        {"id":493,"active":1,"store_id":1},
        {"id":498,"active":1,"store_id":1},
        {"id":500,"active":1,"store_id":1},
        {"id":501,"active":1,"store_id":1},
        {"id":502,"active":1,"store_id":1},
        {"id":503,"active":1,"store_id":1},
        {"id":504,"active":1,"store_id":1},
        {"id":505,"active":1,"store_id":1},
        {"id":509,"active":1,"store_id":1},
        {"id":511,"active":1,"store_id":1},
        {"id":512,"active":1,"store_id":1},
        {"id":515,"active":1,"store_id":1},
        {"id":518,"active":1,"store_id":1},
        {"id":523,"active":1,"store_id":1},
        {"id":524,"active":1,"store_id":1},
        {"id":527,"active":1,"store_id":1},
        {"id":528,"active":1,"store_id":1},
        {"id":533,"active":1,"store_id":1},
        {"id":534,"active":0,"store_id":1},
        {"id":535,"active":1,"store_id":1},
        {"id":539,"active":1,"store_id":1},
        {"id":540,"active":1,"store_id":1},
        {"id":543,"active":1,"store_id":1},
        {"id":546,"active":1,"store_id":1},
        {"id":547,"active":1,"store_id":1},
        {"id":548,"active":1,"store_id":1},
        {"id":549,"active":1,"store_id":1},
        {"id":553,"active":1,"store_id":1},
        {"id":554,"active":1,"store_id":1},
        {"id":555,"active":1,"store_id":1},
        {"id":557,"active":1,"store_id":1},
        {"id":558,"active":0,"store_id":1},
        {"id":560,"active":1,"store_id":1},
        {"id":562,"active":1,"store_id":1},
        {"id":566,"active":1,"store_id":1},
        {"id":572,"active":1,"store_id":1},
        {"id":573,"active":1,"store_id":1},
        {"id":580,"active":1,"store_id":1},
        {"id":581,"active":1,"store_id":1},
        {"id":583,"active":1,"store_id":1},
        {"id":585,"active":1,"store_id":1},
        {"id":586,"active":1,"store_id":1},
        {"id":587,"active":1,"store_id":1},
        {"id":588,"active":1,"store_id":1},
        {"id":589,"active":1,"store_id":1},
        {"id":591,"active":1,"store_id":1},
        {"id":592,"active":0,"store_id":1},
        {"id":594,"active":1,"store_id":1},
        {"id":595,"active":1,"store_id":1},
        {"id":596,"active":1,"store_id":1},
        {"id":597,"active":1,"store_id":1},
        {"id":598,"active":1,"store_id":1}]}]
        */

        // countBy returns an array
        // Inactive customers (active = 0)
        $this->assertSame(8, $stores->find(1)->customers->countBy(fn ($customer) => $customer['active'])[0]);
        // Active customers (active = 1)
        $this->assertSame(318, $stores->find(1)->customers->countBy(fn ($customer) => $customer['active'])[1]);

        $this->assertSame(318, $stores->find(1)->customers->sum(fn ($customer) => $customer['active'] === true));

        // Store 2, count, active and inactive
        $this->assertSame(273, $stores->find(2)->customers->count());
        $this->assertSame(266, $stores->find(2)->customers->sum(fn ($customer) => $customer['active'] === true));
        $this->assertSame(7, $stores->find(2)->customers->sum(fn ($customer) => $customer['active'] === false));
    }
}
