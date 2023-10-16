<?php
namespace App\Filament\Resources;
use Filament\Forms;
use Filament\Tables;
use App\Models\Icbrand;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\IcbrandResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\IcbrandResource\RelationManagers;
class IcbrandResource extends Resource
{
    protected static ?string $model = Icbrand::class;
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Ic Brand';
    protected static ?string $modelLabel = 'Ic Brand';
    protected static ?string $navigationGroup = 'GSM Data';
    protected static ?int $navigationSort = 3;
    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('Admin'), 403);
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('Admin');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live()
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record)
                    ->maxLength(255),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
               TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
               EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIcbrands::route('/'),
            'create' => Pages\CreateIcbrand::route('/create'),
            'edit' => Pages\EditIcbrand::route('/{record}/edit'),
        ];
    }
}
